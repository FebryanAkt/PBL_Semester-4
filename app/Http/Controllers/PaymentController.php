<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Cart;
use App\Notifications\NewOrderNotification;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $clientKey = env('MIDTRANS_CLIENT_KEY');

        // CEK MODE: Beli Langsung atau Dari Keranjang?
        if ($request->has('item_id')) {
            // -- MODE BELI LANGSUNG --
            $item = \App\Models\Item::findOrFail($request->item_id);

            // Ambil kuantitas dari URL, default ke 1 jika kosong
            $qty = $request->input('quantity', 1);

            // Kita buat 'keranjang bohongan' di memori agar file checkout.blade.php 
            // tetap bisa melakukan @foreach tanpa error
            $cart = new \App\Models\Cart();
            $cart->item = $item;
            $cart->quantity = $qty; // <-- Kuantitas dinamis

            $carts = collect([$cart]); // Ubah jadi collection

            // Tandai untuk dibawa ke Javascript
            $isDirectCheckout = true;
            $directItemId = $item->id;
            $directQuantity = $qty; // Simpan kuantitas untuk dikirim via fetch
        } else {
            // -- MODE KERANJANG --
            $query = \App\Models\Cart::with('item')->where('user_id', Auth::id());

            // JIKA ada barang yang dicentang, filter berdasarkan ID-nya
            if ($request->has('cart_ids')) {
                $query->whereIn('id', $request->cart_ids);

                // Simpan ingatan ke Session agar fungsi getToken() nanti juga tahu!
                session(['selected_cart_ids' => $request->cart_ids]);
            } else {
                session()->forget('selected_cart_ids');
            }

            $carts = $query->get();

            if ($carts->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Pilih minimal satu barang untuk di-checkout.');
            }

            $isDirectCheckout = false;
            $directItemId = null;
            $directQuantity = null;
        }

        // Hitung total
        $totalBarang = $carts->sum(function ($cart) {
            return $cart->item->price * $cart->quantity;
        });

        $biayaPlatform = 2500;
        $biayaPenanganan = 1500;
        $totalPembayaran = $totalBarang + $biayaPlatform + $biayaPenanganan;

        return view('checkout.index', compact(
            'clientKey',
            'carts',
            'totalBarang',
            'biayaPlatform',
            'biayaPenanganan',
            'totalPembayaran',
            'isDirectCheckout',
            'directItemId',
            'directQuantity'
        ));
    }

    public function getToken(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $user = Auth::user();

        // CEK ULANG HARGA DI DATABASE (Sesuai parameter dari frontend)
        if ($request->is_direct == 'yes') {
            // Mode Beli Langsung
            $item = \App\Models\Item::findOrFail($request->item_id);
            $qty = $request->input('quantity', 1); // Ambil kuantitas dari fetch JS
            if ($qty > $item->stock) {
                return response()->json([
                    'error' => 'Stok barang tidak mencukupi.'
                ], 400);
            }
            $totalBarang = $item->price * $qty; // <-- Harga dikalikan kuantitas
            $itemToTransaction = $item->id;
        } else {
            // Mode Keranjang
            $query = \App\Models\Cart::with('item')->where('user_id', $user->id);

            // Ambil ingatan dari Session tadi agar Token Midtrans tidak menagih semua isi keranjang
            if (session()->has('selected_cart_ids')) {
                $query->whereIn('id', session('selected_cart_ids'));
            }

            $carts = $query->get();

            if ($carts->isEmpty()) {
                return response()->json(['error' => 'Keranjang kosong atau belum dipilih'], 400);
            }
            // ... (biarkan sisa for-each pengecekan stok di bawahnya tetap sama)
            $totalBarang = $carts->sum(function ($cart) {
                return $cart->item->price * $cart->quantity;
            });
            $qty = $carts->sum('quantity');
            $itemToTransaction = $carts->first()->item_id;
        }

        $grossAmount = $totalBarang + 2500 + 1500; // Tambah biaya admin

        // 1. Buat Order ID Unik di awal
        $orderId = 'TRX-' . time() . '-' . rand(1000, 9999);

        $paymentMethod = $request->payment_method;
        $enabledPayments = [];
        if ($paymentMethod === 'gopay') {
            $enabledPayments = ['gopay'];
        } elseif ($paymentMethod === 'shopeepay') {
            $enabledPayments = ['shopeepay'];
        } elseif ($paymentMethod === 'bca') {
            $enabledPayments = ['bca_va'];
        } elseif ($paymentMethod === 'mandiri') {
            $enabledPayments = ['echannel'];
        } elseif ($paymentMethod === 'bni') {
            $enabledPayments = ['bni_va'];
        } else {
            $enabledPayments = ['other_qris'];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId, // Gunakan Order ID yang dibuat di atas
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $user ? $user->name : 'Pelanggan',
                'email' => $user ? $user->email : 'customer@example.com',
            ],
            'enabled_payments' => $enabledPayments
        ];

        try {
            // 2. Minta Token ke Midtrans terlebih dahulu
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // 3. Simpan Transaksi ke Database (Sekarang beserta order_id & snap_token)
            $transaction = \App\Models\Transaction::create([
                'user_id' => $user->id,
                'item_id' => $itemToTransaction,
                'quantity' => $qty,
                'price' => $grossAmount,
                'status' => 'pending',
                'order_id' => $orderId,      // Menyimpan Order ID
                'snap_token' => $snapToken   // Menyimpan Token
            ]);
            if ($request->is_direct != 'yes') {
                foreach ($carts as $cart) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'cart_id' => $cart->id,
                        'item_id' => $cart->item_id,
                        'quantity' => $cart->quantity,
                        'price' => $cart->item->price,
                    ]);
                }
            }

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $notification = json_decode($request->getContent());

        if (!$notification) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 1. LOG DATA MASUK: Cek apakah Midtrans benar-benar mengirim data
        Log::info('Webhook Midtrans Masuk:', (array) $notification);

        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . $serverKey);

        if ($notification->signature_key != $validSignatureKey) {
            // 2. LOG JIKA SIGNATURE GAGAL
            Log::error('Webhook Gagal: Signature tidak valid!');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = \App\Models\Transaction::where('order_id', $notification->order_id)->first();

        if (!$transaction) {
            // 3. LOG JIKA TRANSAKSI TIDAK KETEMU
            Log::error('Webhook Gagal: Order ID ' . $notification->order_id . ' tidak ditemukan di database.');
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if (in_array($transaction->status, ['success', 'failed'])) {
            Log::info('Webhook diterima untuk transaksi yang sudah final. Status saat ini: ' . $transaction->status);
            return response()->json(['message' => 'Transaction already finalized']);
        }


        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? '';

        // 4. LOG SEBELUM MASUK KE BLOK IF
        Log::info('Berhasil melewati keamanan. Status dari Midtrans: ' . $transactionStatus);

        // --- BLOK IF KAMU DIMULAI DI SINI ---
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {

            if ($fraudStatus == 'challenge') {

                $transaction->status = 'pending';
            } else {

                $transaction->status = 'success';

                $transaction->load('transactionItems.item');

                if ($transaction->transactionItems->count() > 0) {

                    foreach ($transaction->transactionItems as $detail) {

                        $item = $detail->item;

                        if ($item) {

                            $item->stock -= $detail->quantity;

                            if ($item->stock <= 0) {
                                $item->stock = 0;
                                $item->status = 'terjual';
                            }

                            $item->save();
                            $penjual = $item->user;
                            if ($penjual) {
                                $penjual->notify(new NewOrderNotification($transaction));
                            }
                        }
                    }
                } else {

                    $item = \App\Models\Item::find($transaction->item_id);

                    if ($item) {

                        $item->stock -= $transaction->quantity;

                        if ($item->stock <= 0) {
                            $item->stock = 0;
                            $item->status = 'terjual';
                        }

                        $item->save();
                        $penjual = $item->user;
                        if ($penjual) {
                            $penjual->notify(new NewOrderNotification($transaction));
                        }
                    }
                }
            }
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transaction->status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
            if ($transaction->status != 'success' && $transaction->status != 'failed') {
                $transaction->status = 'pending';
            }
        }

        $transaction->save();
        if ($transaction->status == 'success') {

            $cartIds = $transaction->transactionItems
                ->pluck('cart_id')
                ->filter();

            \App\Models\Cart::whereIn('id', $cartIds)->delete();
        }

        Log::info('Selesai memproses Webhook dan database berhasil diupdate.');
        return response()->json(['message' => 'Webhook success']);
    }

    public function history()
    {
        // Mengambil transaksi milik user yang sedang login beserta relasi item-nya
        $transactions = \App\Models\Transaction::with('item')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('transaction.index', compact('transactions'));
    }

    public function detail($id)
    {
        $transaction = Transaction::with('item')->where('user_id', auth()->id())->findOrFail($id);

        // Data yang akan dikirim ke modal
        return response()->json([
            'item_name'        => $transaction->item?->name ?? 'Barang tidak tersedia',
            'item_image'       => $transaction->item?->image ? asset('images/' . $transaction->item->image) : null,
            'category'         => $transaction->item?->category,
            'created_at'       => $transaction->created_at->format('d M Y, H:i'),
            'price_formatted'  => 'Rp ' . number_format($transaction->price, 0, ',', '.'),
            'status'           => $transaction->status,
            'delivery_status'  => $transaction->delivery_status ?? 'belum_dikirim',
            'payment_method'   => $transaction->payment_method ?? 'Belum tersedia', // optional nanti
            'notes'            => $transaction->notes ?? null
        ]);
    }

    public function confirmDelivery($id)
    {
        // Cari transaksi berdasarkan ID dan pastikan itu milik pembeli yang sedang login
        $transaction = \App\Models\Transaction::where('user_id', auth()->id())->findOrFail($id);

        // Pastikan statusnya memang sedang "dikirim"
        if ($transaction->delivery_status == 'dikirim') {
            $transaction->delivery_status = 'diterima';
            $transaction->save();

            return redirect()->back()->with('success', 'Hore! Transaksi selesai. Terima kasih telah mengkonfirmasi penerimaan barang!');
        }

        return redirect()->back()->with('error', 'Status pesanan tidak valid untuk dikonfirmasi.');
    }
}
