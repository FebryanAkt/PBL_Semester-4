<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
public function checkout()
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Ambil data user yang sedang login dengan aman
        $user = Auth::user();

        $params = [
            'transaction_details' => [
                'order_id' => rand(), 
                'gross_amount' => 2504000, 
            ],
            'customer_details' => [
                // Jika $user ada isinya, ambil name/email. Jika tidak, pakai teks default.
                'first_name' => $user ? $user->name : 'Pelanggan',
                'email' => $user ? $user->email : 'customer@example.com',
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        $clientKey = env('MIDTRANS_CLIENT_KEY');

        return view('checkout', compact('snapToken', 'clientKey'));
    }

    public function callback(Request $request)
    {
        // Nanti kita isi logika untuk menerima status sukses/gagal dari Midtrans di sini
        return response()->json(['message' => 'Callback diterima']);
    }
}