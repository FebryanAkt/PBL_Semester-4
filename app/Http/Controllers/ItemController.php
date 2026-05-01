<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function landing()
    {
        $items = Item::latest()->get();
        return view('home', compact('items'));
    }

    public function index()
    {
        $items = Item::latest()->get();
        return view('home', compact('items'));
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return view('item.show', compact('item'));
    }


    public function myItems()
    {
        $items = Item::latest()->get();
        
        $total = $items->count();
        $tersedia = $items->where('status', 'tersedia')->count();
        $booking = $items->where('status', 'booking')->count();
        $terjual = $items->where('status', 'terjual')->count();

        return view('item.index', compact('items', 'total', 'tersedia', 'booking', 'terjual'));
    }

    // EDIT BARANG
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('item.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'nullable',
            'description' => 'nullable',
            'tags' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
            $item->image = $imagePath;
        }

        $item->update([
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'tags' => $request->tags,
        ]);

        return redirect()->route('item.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function jual()
    {
        
        return view('item.sell'); 
    }

}
