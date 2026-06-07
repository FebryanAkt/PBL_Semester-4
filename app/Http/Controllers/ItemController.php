<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
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
        return $this->renderMarketplace($request);
    }

    public function index(Request $request)
    {
        if (Auth::user()?->isSeller() && $request->routeIs('home')) {
            return redirect()->route('penjual.home', $request->query());
        }

        return $this->renderMarketplace($request);
    }

    public function sellerHome(Request $request)
    {
        if (!Auth::user()?->isSeller()) {
            return redirect()->route('home');
        }

        return $this->renderMarketplace($request);
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


    public function myItems()
    {
        if ($response = $this->denyBuyerIfNeeded()) {
            return $response;
        }

        $items = Item::where('user_id', Auth::id())->get();

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

        // Validasi
        $request->validate([
            'nama_barang' => 'required|min:5',
            'harga'       => 'required|numeric',
            'kategori'    => 'required|exists:categories,id',
            'stock' => 'required|integer|min:1',
            'lokasi'      => 'required',
            'nomor_telp'  => 'required|string|min:10|max:15', // <--- TAMBAHAN VALIDASI
            'kondisi'     => 'required',
            'foto_utama'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_tambahan.*' => 'image|mimes:jpg,jpeg,png|max:2048',
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

        $additionalImages = [];

        if ($request->hasFile('foto_tambahan')) {

            foreach ($request->file('foto_tambahan') as $image) {

                $imageName = time() . '_' . $image->getClientOriginalName();

                $image->move(public_path('images'), $imageName);

                $additionalImages[] = $imageName;
            }
        }

        // Simpan data
        Item::create([
            'user_id'     => Auth::id(),
            'name'        => $request->nama_barang,
            'price'       => $request->harga,
            'stock' => $request->stock,
            'category_id' => $request->kategori,
            'location'    => $request->lokasi,
            'phone'       => $request->nomor_telp, // <--- SIMPAN KE DATABASE
            'condition'   => $request->kondisi,
            'description' => $request->deskripsi,
            'image'       => $fileName,
            'images'      => json_encode($additionalImages),
            'status'      => 'tersedia', // <--- Set default langsung di sini
        ]);

        return redirect()
            ->route('barang.saya')
            ->with('success', 'Barang berhasil diposting!');
    }
}
