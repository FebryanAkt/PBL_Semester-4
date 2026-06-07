<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

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
            $carts = \App\Models\Cart::with('item')->where('user_id', Auth::id())->get();

            if ($carts->isEmpty()) {
                return redirect()->route('home')->with('error', 'Keranjang belanja Anda masih kosong.');
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
            $qty = $request->input('quantity', 1);

            $totalBarang = $item->price * $qty;
            $itemToTransaction = $item->id;
        } else {
            // Mode Keranjang
            $carts = \App\Models\Cart::with('item')->where('user_id', $user->id)->get();
            if ($carts->isEmpty()) {
                return response()->json(['error' => 'Keranjang kosong'], 400);
            }
            $totalBarang = $carts->sum(function ($cart) {
                return $cart->item->price * $cart->quantity;
            });
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
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'item_id' => $itemToTransaction,
                'price' => $grossAmount,
                'status' => 'pending',
                'order_id' => $orderId,      // Menyimpan Order ID
                'snap_token' => $snapToken   // Menyimpan Token
            ]);

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
                if ($transaction->status != 'success' && $transaction->status != 'failed') {
                    $transaction->status = 'pending';
                }
            } else {
                $transaction->status = 'success';

                $item = \App\Models\Item::find($transaction->item_id);
                if ($item) {
                    $item->status = 'terjual';
                    $item->save();
                    Log::info('Status Item berhasil diubah jadi terjual');
                }
            }
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $transaction->status = 'failed';
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        }

        $transaction->save();

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
}
