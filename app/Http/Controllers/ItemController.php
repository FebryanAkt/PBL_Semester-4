<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    private function denyBuyerIfNeeded()
    {
        if (!Auth::user()?->isSeller()) {
            return redirect()
                ->route('home')
                ->with('success', 'Akun pembeli tidak memiliki akses ke fitur jual barang.');
        }

        return null;
    }

    public function landing(Request $request)
    {
        $query = Item::latest();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('kategori') && $request->kategori != 'Semua Kategori' && $request->kategori != '') {
            $kategori = trim(preg_replace('/[^\p{L}\p{N}\s]/u', '', $request->kategori));
            $query->where('category', 'LIKE', '%' . $kategori . '%');
        }

        if ($request->has('kecamatan') && $request->kecamatan != 'Semua Kecamatan' && $request->kecamatan != '') {
            $query->where('location', 'LIKE', '%' . $request->kecamatan . '%');
        }

        if ($request->has('kondisi') && $request->kondisi != 'Semua Kondisi' && $request->kondisi != '') {
            $query->where('condition', 'LIKE', '%' . $request->kondisi . '%');
        }

        if ($request->has('harga') && $request->harga != 'Urutkan Harga' && $request->harga != '') {
            if ($request->harga == 'Termurah') {
                $query->getQuery()->orders = null;
                $query->orderBy('price', 'asc');
            } elseif ($request->harga == 'Termahal') {
                $query->getQuery()->orders = null;
                $query->orderBy('price', 'desc');
            }
        }

        $items = $query->get();
        return view('home', compact('items'));
    }

    public function index(Request $request)
    {
        $query = Item::latest();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($request->has('kategori') && $request->kategori != 'Semua Kategori' && $request->kategori != '') {
            $kategori = trim(preg_replace('/[^\p{L}\p{N}\s]/u', '', $request->kategori));
            $query->where('category', 'LIKE', '%' . $kategori . '%');
        }

        if ($request->has('kecamatan') && $request->kecamatan != 'Semua Kecamatan' && $request->kecamatan != '') {
            $query->where('location', 'LIKE', '%' . $request->kecamatan . '%');
        }

        if ($request->has('kondisi') && $request->kondisi != 'Semua Kondisi' && $request->kondisi != '') {
            $query->where('condition', 'LIKE', '%' . $request->kondisi . '%');
        }

        if ($request->has('harga') && $request->harga != 'Urutkan Harga' && $request->harga != '') {
            if ($request->harga == 'Termurah') {
                $query->getQuery()->orders = null;
                $query->orderBy('price', 'asc');
            } elseif ($request->harga == 'Termahal') {
                $query->getQuery()->orders = null;
                $query->orderBy('price', 'desc');
            }
        }

        $items = $query->get();
        return view('home', compact('items'));
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);
        return view('item.show', compact('item'));
    }


    public function myItems()
    {
        if ($response = $this->denyBuyerIfNeeded()) {
            return $response;
        }

        $items = Item::where('user_id', Auth::id())->latest()->get();
        
        $total = $items->count();
        $tersedia = $items->where('status', 'tersedia')->count();
        $booking = $items->where('status', 'booking')->count();
        $terjual = $items->where('status', 'terjual')->count();

        return view('item.index', compact('items', 'total', 'tersedia', 'booking', 'terjual'));
    }

    // EDIT BARANG
    public function edit($id)
    {
        if ($response = $this->denyBuyerIfNeeded()) {
            return $response;
        }

        $item = Item::findOrFail($id);
        abort_if(!Auth::user()->isAdmin() && (int) $item->user_id !== (int) Auth::id(), 403);

        return view('item.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->denyBuyerIfNeeded()) {
            return $response;
        }

        $item = Item::findOrFail($id);
        abort_if(!Auth::user()->isAdmin() && (int) $item->user_id !== (int) Auth::id(), 403);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'nullable',
            'description' => 'nullable',
            'tags' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:tersedia,booking,terjual',
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
            'status' => $request->status,
        ]);
        //Diarahkan ke halaman barang
        return redirect()->route('barang.saya')->with('success', 'Barang berhasil diupdate!');
    }

    public function jual()
    {
        if ($response = $this->denyBuyerIfNeeded()) {
            return $response;
        }
        
        return view('item.sell'); 
    }

public function jual_simpan(Request $request)
    {
        if ($response = $this->denyBuyerIfNeeded()) {
            return $response;
        }

        // Validasi
        $request->validate([
            'nama_barang' => 'required|min:5',
            'harga'       => 'required|numeric',
            'kategori'    => 'required',
            'lokasi'      => 'required',
            'nomor_telp'  => 'required|string|min:10|max:15', // <--- TAMBAHAN VALIDASI
            'kondisi'     => 'required',
            'foto_utama'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
            // Hapus baris validasi status jika di form tidak ada input status, 
            // biarkan default database yang bekerja (tersedia)
        ]);

        // Upload gambar
        $fileName = null;
        if ($request->hasFile('foto_utama')) {
            $file = $request->file('foto_utama');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('images'), $fileName);
        }

        // Simpan data
        Item::create([
            'user_id'     => Auth::id(),
            'name'        => $request->nama_barang,
            'price'       => $request->harga,
            'category'    => $request->kategori,
            'location'    => $request->lokasi,
            'phone'       => $request->nomor_telp, // <--- SIMPAN KE DATABASE
            'condition'   => $request->kondisi,
            'description' => $request->deskripsi,
            'image'       => $fileName,
            'status'      => 'tersedia', // <--- Set default langsung di sini
        ]);

        return redirect()
            ->route('barang.saya')
            ->with('success', 'Barang berhasil diposting!');
    }

}
