<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
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
        if (Auth::user()?->isSeller()) {
            return redirect()->route('barang.saya');
        }

        return $this->renderMarketplace($request);
    }

    public function index(Request $request)
    {
        if (Auth::user()?->isSeller()) {
            return redirect()->route('barang.saya');
        }

        return $this->renderMarketplace($request);
    }

    public function sellerHome(Request $request)
    {
        if (!Auth::user()?->isSeller()) {
            return redirect()->route('home');
        }

        return redirect()->route('barang.saya');
    }

    private function renderMarketplace(Request $request)
    {
        $items = $this->marketplaceQuery($request)->get();
        $filterOptions = $this->filterOptions();

        return view('home', compact('items', 'filterOptions'));
    }

    private function marketplaceQuery(Request $request): Builder
    {
        $query = Item::query()
            ->with('categoryRecord')
            ->where('status', '!=', 'terjual');

        if ($request->filled('search')) {
            $searchTerm = trim((string) $request->input('search'));

            $query->where(function (Builder $query) use ($searchTerm) {
                $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $category = $request->input('kategori');
        if ($category && $category !== 'Semua Kategori') {
            $query->where(function (Builder $query) use ($category) {
                $query->where('category', $category)
                    ->orWhereHas('categoryRecord', function (Builder $categoryQuery) use ($category) {
                        $categoryQuery->where('name', $category);
                    });
            });
        }

        $district = $request->input('kecamatan');
        if ($district && $district !== 'Semua Kecamatan') {
            $query->where('location', 'LIKE', '%' . $district . '%');
        }

        $condition = $request->input('kondisi');
        if ($condition && $condition !== 'Semua Kondisi') {
            $query->where('condition', $condition);
        }

        return match ($request->input('harga')) {
            'Termurah' => $query->orderBy('price')->orderBy('id'),
            'Termahal' => $query->orderByDesc('price')->orderByDesc('id'),
            default => $query->latest(),
        };
    }

    private function filterOptions(): array
    {
        $categories = Category::query()
            ->orderBy('name')
            ->pluck('name')
            ->all();

        $legacyCategories = Item::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->all();

        $conditions = Item::query()
            ->whereNotNull('condition')
            ->where('condition', '!=', '')
            ->distinct()
            ->orderBy('condition')
            ->pluck('condition')
            ->all();

        return [
            'categories' => array_values(array_unique(array_merge($categories, $legacyCategories))),
            'districts' => ['Blimbing', 'Kedungkandang', 'Klojen', 'Lowokwaru', 'Sukun'],
            'conditions' => array_values(array_unique(array_merge(
                ['Bekas', 'Sangat Baik', 'Baik', 'Minus Pemakaian'],
                $conditions
            ))),
            'prices' => ['Termurah', 'Termahal'],
        ];
    }

    public function show($id)
    {
        $item = Item::with('user')->findOrFail($id);
        return view('item.show', compact('item'));
    }


    public function myItems(Request $request)
    {
        if ($response = $this->denyBuyerIfNeeded()) {
        return $response;
        }
        $filter = $request->get('filter', 'all');

        $query = Item::where('user_id', Auth::id());

        $total = (clone $query)->count();
        $tersedia = (clone $query)->where('status', 'tersedia')->count();
        $pesanan = Transaction::query()
            ->activeForSeller((int) Auth::id())
            ->count();
        $terjual = (clone $query)->where('status', 'terjual')->count();

        switch ($filter) {
            case 'tersedia':
                $query->where('status', 'tersedia');
                break;

            case 'booking':
                $query->where('status', 'booking');
                break;

            case 'terjual':
                $query->where('status', 'terjual');
                break;
        }

        $items = $query->latest()->get();

        return view(
            'item.index',
            compact(
                'items',
                'total',
                'tersedia',
                'pesanan',
                'terjual',
                'filter'
            )
        );
    }

    // EDIT BARANG
    public function edit($id)
    {
        if ($response = $this->denyBuyerIfNeeded()) {
            return $response;
        }

        $item = Item::findOrFail($id);
        abort_if(!Auth::user()->isAdmin() && (int) $item->user_id !== (int) Auth::id(), 403);
        $categories = Category::query()->orderBy('name')->get();

        return view('item.edit', compact('item', 'categories'));
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
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'tags' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:tersedia,booking,terjual',
            'stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
            $item->image = $imagePath;
        }

        $item->update([
            'name' => $request->name,
            'price' => $request->price,
            'category' => null,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'tags' => $request->tags,
            'status' => $request->status,
             'stock' => $request->stock,
        ]);

        if ($request->stock > 0) {
            $item->status = 'tersedia';
            $item->save();
        }
        //Diarahkan ke halaman barang
        return redirect()->route('barang.saya')->with('success', 'Barang berhasil diupdate!');
    }

    public function jual()
    {
        if ($response = $this->denyBuyerIfNeeded()) {
            return $response;
        }

        $categories = Category::query()->orderBy('name')->get();

        return view('item.sell', compact('categories'));
    }

    public function jual_simpan(Request $request)
    {
        if ($response = $this->denyBuyerIfNeeded()) {
        return $response;
    }

    try {

        $request->validate([
            'nama_barang' => 'required|min:5',
            'harga'       => 'required|numeric',
            'kategori'    => 'required|exists:categories,id',
            'stock'       => 'required|integer|min:1',
            'lokasi'      => 'required',
            'nomor_telp'  => 'required|string|min:10|max:15',
            'kondisi'     => 'required',
            'foto_utama'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_tambahan.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ],[
            // List pesan kustom agar pop-up di blade jadi rapi dan manusiawi
            'nama_barang.required' => 'Nama barang tidak boleh kosong.',
            'nama_barang.min'      => 'Nama barang minimal harus 5 karakter.',
            'harga.required'       => 'Harga barang wajib diisi.',
            'harga.numeric'        => 'Harga harus berupa angka.',
            'kategori.required'    => 'Silakan pilih kategori barang.',
            'kategori.exists'      => 'Kategori yang dipilih tidak valid.',
            'stock.required'       => 'Jumlah stok wajib diisi.',
            'stock.min'            => 'Stok minimal adalah 1 barang.',
            'lokasi.required'      => 'Lokasi keberadaan barang wajib diisi.',
            'nomor_telp.required'  => 'Nomor telepon wajib diisi untuk hubungi penjual.',
            'nomor_telp.min'       => 'Nomor telepon minimal 10 digit.',
            'kondisi.required'     => 'Pilih kondisi barang bekasmu saat ini.',
            'foto_utama.required'  => 'Foto utama produk wajib diunggah.',
            'foto_utama.image'     => 'Berkas utama harus berupa file gambar.',
            'foto_utama.max'       => 'Ukuran foto utama maksimal adalah 2 MB.',
            'foto_tambahan.*.image' => 'Semua foto tambahan harus berupa file gambar.',
            'foto_tambahan.*.max'   => 'Ukuran foto tambahan maksimal adalah 2 MB per file.',

        ]);

        $fileName = null;

        if ($request->hasFile('foto_utama')) {
            $file = $request->file('foto_utama');

            $fileName = time() . '_' .
                str_replace(' ', '_', $file->getClientOriginalName());

            $file->move(public_path('images'), $fileName);
        }

        $additionalImages = [];

        if ($request->hasFile('foto_tambahan')) {

            foreach ($request->file('foto_tambahan') as $image) {

                $imageName = time() . '_' . $image->getClientOriginalName();

                $image->move(public_path('images'), $imageName);

                $additionalImages[] = $imageName;
            }
        }

        Item::create([
            'user_id'     => Auth::id(),
            'name'        => $request->nama_barang,
            'price'       => $request->harga,
            'stock'       => $request->stock,
            'category_id' => $request->kategori,
            'location'    => $request->lokasi,
            'phone'       => $request->nomor_telp,
            'condition'   => $request->kondisi,
            'description' => $request->deskripsi,
            'image'       => $fileName,
            'images'      => json_encode($additionalImages),
            'status'      => 'tersedia',
        ]);

        return redirect()
            ->route('barang.saya')
            ->with('success', 'Barang berhasil diposting!');

    } catch (\Illuminate\Validation\ValidationException $e) {

        return back()
            ->withErrors($e->validator)
            ->withInput();

    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
    }

     return redirect()
            ->route('barang.saya')
            ->with('success', 'Barang berhasil diposting!');
    }
}
