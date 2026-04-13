<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua data barang dari database
        $items = Item::latest()->get(); 
        
        // Kirim data ke file welcome.blade.php
        return view('welcome', compact('items')); 
    }
}