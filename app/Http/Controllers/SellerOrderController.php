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

        $transactions = Transaction::with(['item', 'user', 'transactionItems.item'])
            ->where(function ($query) use ($user) {
                $query
                    ->whereHas('item', fn ($itemQuery) => $itemQuery->where('user_id', $user->id))
                    ->orWhereHas('transactionItems.item', fn ($itemQuery) => $itemQuery->where('user_id', $user->id));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $sellerId = (int) $user->id;

        return view('penjual.orders.index', compact('transactions', 'sellerId'));
    }

    /**
     * Detail pesanan tertentu
     */
    public function show($id)
    {
        $user = Auth::user();
        if (!$user->isSeller()) abort(403);

        $transaction = Transaction::with(['item', 'user', 'transactionItems.item'])
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                $query
                    ->whereHas('item', fn ($itemQuery) => $itemQuery->where('user_id', $user->id))
                    ->orWhereHas('transactionItems.item', fn ($itemQuery) => $itemQuery->where('user_id', $user->id));
            })
            ->firstOrFail();

        $sellerId = (int) $user->id;
        $sellerItems = $transaction->orderLinesForSeller($sellerId);

        return view('penjual.orders.show', compact('transaction', 'sellerId', 'sellerItems'));
    }

    /**
     * Update status pengiriman (misal: tandai sudah dikirim)
     */
    public function updateDelivery(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isSeller()) abort(403);

        $transaction = Transaction::with(['item', 'transactionItems.item'])
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                $query
                    ->whereHas('item', fn ($itemQuery) => $itemQuery->where('user_id', $user->id))
                    ->orWhereHas('transactionItems.item', fn ($itemQuery) => $itemQuery->where('user_id', $user->id));
            })
            ->firstOrFail();

        $validated = $request->validate([
            'delivery_status' => 'required|in:belum_dikirim,dikirim,diterima',
            'shipping_code' => 'nullable|string|max:100',
        ]);

        $sellerItems = $transaction->transactionItems
            ->filter(fn ($line) => (int) $line->item?->user_id === (int) $user->id);

        if ($sellerItems->isNotEmpty()) {
            foreach ($sellerItems as $sellerItem) {
                $sellerItem->update([
                    'delivery_status' => $validated['delivery_status'],
                    'shipping_code' => $validated['shipping_code'] ?? null,
                ]);
            }
        } else {
            $transaction->delivery_status = $validated['delivery_status'];
            $transaction->shipping_code = $validated['shipping_code'] ?? null;
            $transaction->save();
        }

        return redirect()->route('penjual.orders.show', $transaction->id)
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
