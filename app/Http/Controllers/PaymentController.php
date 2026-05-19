<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
public function checkout(Request $request)
    {
        $clientKey = env('MIDTRANS_CLIENT_KEY');
        
        // CEK MODE: Beli Langsung atau Dari Keranjang?
        if ($request->has('item_id')) {
            // -- MODE BELI LANGSUNG --
            $item = \App\Models\Item::findOrFail($request->item_id);
            
            // Kita buat 'keranjang bohongan' di memori agar file checkout.blade.php 
            // tetap bisa melakukan @foreach tanpa error
            $cart = new \App\Models\Cart();
            $cart->item = $item;
            $cart->quantity = 1;
            
            $carts = collect([$cart]); // Ubah jadi collection
            
            // Tandai untuk dibawa ke Javascript
            $isDirectCheckout = true;
            $directItemId = $item->id;
        } else {
            // -- MODE KERANJANG --
            $carts = \App\Models\Cart::with('item')->where('user_id', Auth::id())->get();

            if ($carts->isEmpty()) {
                return redirect()->route('home')->with('error', 'Keranjang belanja Anda masih kosong.');
            }

            $isDirectCheckout = false;
            $directItemId = null;
        }

        // Hitung total
        $totalBarang = $carts->sum(function($cart) {
            return $cart->item->price * $cart->quantity;
        });

        $biayaPlatform = 2500;
        $biayaPenanganan = 1500;
        $totalPembayaran = $totalBarang + $biayaPlatform + $biayaPenanganan;

        return view('checkout', compact(
            'clientKey', 'carts', 'totalBarang', 'biayaPlatform', 
            'biayaPenanganan', 'totalPembayaran', 'isDirectCheckout', 'directItemId'
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
            $totalBarang = $item->price;
            $itemToTransaction = $item->id;
        } else {
            // Mode Keranjang
            $carts = \App\Models\Cart::with('item')->where('user_id', $user->id)->get();
            if ($carts->isEmpty()) {
                return response()->json(['error' => 'Keranjang kosong'], 400);
            }
            $totalBarang = $carts->sum(function($cart) {
                return $cart->item->price * $cart->quantity;
            });
            $itemToTransaction = $carts->first()->item_id;
        }

        $grossAmount = $totalBarang + 2500 + 1500; // Tambah biaya admin

        // Buat Transaksi
        $transaction = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'item_id' => $itemToTransaction, 
            'price' => $grossAmount,
            'status' => 'pending'
        ]);

        $paymentMethod = $request->payment_method;
        $enabledPayments = [];
        if ($paymentMethod === 'gopay') { $enabledPayments = ['gopay']; } 
        elseif ($paymentMethod === 'shopeepay') { $enabledPayments = ['shopeepay']; } 
        elseif ($paymentMethod === 'bca') { $enabledPayments = ['bca_va']; } 
        elseif ($paymentMethod === 'mandiri') { $enabledPayments = ['echannel']; } 
        elseif ($paymentMethod === 'bni') { $enabledPayments = ['bni_va']; } 
        else { $enabledPayments = ['other_qris']; }

        $params = [
            'transaction_details' => [
                'order_id' => 'TRX-' . $transaction->id . '-' . time(), 
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $user ? $user->name : 'Pelanggan',
                'email' => $user ? $user->email : 'customer@example.com',
            ],
            'enabled_payments' => $enabledPayments
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        return response()->json(['message' => 'Callback diterima']);
    }
}