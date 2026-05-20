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
    public function add(Request $request)
    {
        $user_id = Auth::id();

        // Ambil item_id dari hidden input form request
        $itemId = $request->input('item_id');

        // Ambil kuantitas dari request form, jika tidak ada default ke 1
        $qty = $request->input('quantity', 1);

        // Cek apakah barang sudah ada di keranjang user ini
        $existingCart = Cart::where('user_id', $user_id)->where('item_id', $itemId)->first();

        if ($existingCart) {
            // Jika sudah ada, tambahkan jumlahnya (quantity) dengan $qty baru
            $existingCart->update([
                'quantity' => $existingCart->quantity + $qty
            ]);
        } else {
            // Jika belum ada, buat baru dengan quantity $qty
            Cart::create([
                'user_id' => $user_id,
                'item_id' => $itemId,
                'quantity' => $qty
            ]);
        }

        // TAMBAHAN: Hitung total kuantitas keranjang terbaru untuk user ini
        $totalCartCount = Cart::where('user_id', $user_id)->sum('quantity');

        // TANGGAPAN UNTUK AJAX REQUEST
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan ke keranjang!',
                'cart_count' => $totalCartCount // <-- Kirim total terbaru ke frontend
            ]);
        }

        // Tanggapan normal
        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

    // Mengupdate jumlah barang (Plus/Minus) di keranjang
    public function update(Request $request, $id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($request->action == 'plus') {
            $cart->increment('quantity');
        } elseif ($request->action == 'minus') {
            // Pastikan quantity tidak kurang dari 1. Jika 1, abaikan atau biarkan user pakai tombol hapus.
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            }
        }

        return redirect()->back(); // Halaman akan otomatis refresh dan menghitung ulang total
    }

    // Menghapus barang dari keranjang
    public function remove($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cart->delete();

        return redirect()->back()->with('success', 'Barang dihapus dari keranjang.');
    }
}
