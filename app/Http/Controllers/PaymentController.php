<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function checkout()
    {
        $clientKey = env('MIDTRANS_CLIENT_KEY');
        return view('checkout', compact('clientKey'));
    }

    public function getToken(Request $request)
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $user = Auth::user();

        // 1. Tangkap pilihan radio button dari frontend
        $paymentMethod = $request->payment_method;

        // 2. Cocokkan value radio button dengan kode Midtrans
        $enabledPayments = [];
        if ($paymentMethod === 'gopay') {
            $enabledPayments = ['gopay'];
        } elseif ($paymentMethod === 'shopeepay') {
            $enabledPayments = ['shopeepay'];
        } elseif ($paymentMethod === 'bca') {
            $enabledPayments = ['bca_va'];
        } elseif ($paymentMethod === 'mandiri') {
            $enabledPayments = ['echannel']; // Di Midtrans, Mandiri VA disebut echannel
        } elseif ($paymentMethod === 'bni') {
            $enabledPayments = ['bni_va'];
        } else {
            // Untuk DANA / OVO biasanya menggunakan QRIS jika belum integrasi B2B khusus
            $enabledPayments = ['other_qris'];
        }

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 2504000,
            ],
            'customer_details' => [
                'first_name' => $user ? $user->name : 'Pelanggan',
                'email' => $user ? $user->email : 'customer@example.com',
            ],
            // 3. MASUKKAN PARAMETER INI UNTUK BYPASS MENU MIDTRANS
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
        // Nanti kita isi logika untuk menerima status sukses/gagal dari Midtrans di sini
        return response()->json(['message' => 'Callback diterima']);
    }
}
