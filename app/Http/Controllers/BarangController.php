<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class BarangController extends Controller
{
    public function show($id)
    {
        $item = Item::findOrFail($id);
        return view('barang.show', compact('item'));
    }

    public function detail($id)
    {
        $item = Item::with('user')->findOrFail($id);
        return view('produk.detail', compact('item'));
    }
}
