<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerOrderController extends Controller
{
    /**
     * Daftar pesanan masuk untuk penjual (barang miliknya)
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->isSeller()) abort(403);

        $transactions = Transaction::with(['item', 'user'])
            ->whereHas('item', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('penjual.orders.index', compact('transactions'));
    }

    /**
     * Detail pesanan tertentu
     */
    public function show($id)
    {
        $user = Auth::user();
        $transactions = Transaction::with(['item', 'user'])
            ->whereHas('item', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('penjual.orders.index', compact('transactions'));
    }

    /**
     * Update status pengiriman (misal: tandai sudah dikirim)
     */
    public function updateDelivery(Request $request, $id)
    {
        $user = Auth::user();
        $transaction = Transaction::where('id', $id)
            ->whereHas('item', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->firstOrFail();

        $request->validate([
            'delivery_status' => 'required|in:belum_dikirim,dikirim,diterima',
            'shipping_code' => 'nullable|string|max=100',
        ]);

        $transaction->delivery_status = $request->delivery_status;
        if ($request->filled('shipping_code')) {
            // Jika perlu tambahkan kolom shipping_code di migration, opsional
            // $transaction->shipping_code = $request->shipping_code;
        }
        $transaction->save();

        return redirect()->route('penjual.orders.show', $transaction->id)
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
