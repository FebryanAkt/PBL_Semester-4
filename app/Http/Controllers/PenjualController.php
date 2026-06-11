<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;

class PenjualController extends Controller
{
    public function lapak($id)
    {
        $user = User::findOrFail($id);
        $items = Item::where('user_id', $user->id)->get();

        return view('penjual.lapak', compact('user', 'items'));
    }
}