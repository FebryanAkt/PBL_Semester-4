<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        // Ambil data keranjang milik user yang sedang login beserta data barangnya
        $carts = Cart::with('item')->where('user_id', Auth::id())->get();
        
        return view('cart.index', compact('carts'));
    }

    // Menambahkan barang ke keranjang
    public function add(Request $request, $itemId)
    {
        $user_id = Auth::id();

        // Cek apakah barang sudah ada di keranjang user ini
        $existingCart = Cart::where('user_id', $user_id)->where('item_id', $itemId)->first();

        if ($existingCart) {
            // Jika sudah ada, tambahkan jumlahnya (quantity)
            $existingCart->update([
                'quantity' => $existingCart->quantity + 1
            ]);
        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_id' => $user_id,
                'item_id' => $itemId,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

    // Menghapus barang dari keranjang
    public function remove($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cart->delete();

        return redirect()->back()->with('success', 'Barang dihapus dari keranjang.');
    }
}