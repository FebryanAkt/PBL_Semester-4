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
}
