<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();
        return view('home', compact('items'));
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return view('detail', compact('item'));
    }

     // BARANG SAYA
    public function myItems()
    {
        $items = Item::latest()->get();
        
        $total = $items->count();
        $tersedia = $items->where('status', 'tersedia')->count();
        $booking = $items->where('status', 'booking')->count();
        $terjual = $items->where('status', 'terjual')->count();

        return view('barang-saya', compact('items', 'total', 'tersedia', 'booking', 'terjual'));
    }

    // // FORM TAMBAH
    // public function create()
    // {
    //     return view('tambah-barang');
    // }

    //  // SIMPAN
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //         'price' => 'required|numeric',
    //         'location' => 'required',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $imagePath = null;

    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('items', 'public');
    //     }

    //     Item::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //         'location' => $request->location,
    //         'image' => $imagePath,
    //         'condition' => 'Bekas',
    //         'status' => 'tersedia',
    //     ]);
    //     return redirect()->route('barang.saya')->with('success', 'Barang berhasil ditambahkan!');
    // }

}
