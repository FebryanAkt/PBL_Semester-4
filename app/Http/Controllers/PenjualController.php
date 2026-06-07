<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item; 

class PenjualController extends Controller
{
    public function index()
    {
        // Ambil semua barang milik penjual yang sedang login
        $items = Item::where('user_id', auth()->id())->get();

        return view('penjual.dashboard', compact('items'));
    }
}