@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold mb-6 text-bekas-dark">Edit Produk</h1>

    <form action="{{ route('barang.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-blue-50 p-8 rounded-xl shadow-md space-y-6">

            {{-- Nama Produk --}}
            <div>
                <label class="block text-sm font-semibold mb-2">Produk</label>
                <input type="text" name="name" value="{{ $item->name }}" 
                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring focus:ring-bekas-dark">
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-sm font-semibold mb-2">Kategori</label>
                <select name="category" 
                        class="w-full p-3 rounded-lg border border-gray-300 focus:ring focus:ring-bekas-dark">
                    <option value="Elektronik" {{ $item->category == 'Elektronik' ? 'selected' : '' }}>💻Elektronik</option>
                    <option value="Furniture" {{ $item->category == 'Furniture' ? 'selected' : '' }}>🪑Furniture</option>
                    <option value="Fashion" {{ $item->category == 'Fashion' ? 'selected' : '' }}>👕Fashion</option>
                    <option value="Hobi" {{ $item->category == 'Hobi' ? 'selected' : '' }}>🎸Hobi</option>
                </select>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-semibold mb-2">Deskripsi</label>
                <textarea name="description" rows="5" 
                          class="w-full p-3 rounded-lg border border-gray-300 focus:ring focus:ring-bekas-dark">{{ $item->description }}</textarea>
            </div>

            {{-- Upload Gambar --}}
            <div>
                <label class="block text-sm font-semibold mb-2">Unggah Gambar</label>
                <input type="file" name="image" 
                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring focus:ring-bekas-dark">
            </div>

            {{-- Harga --}}
            <div>
                <label class="block text-sm font-semibold mb-2">Harga</label>
                <input type="number" name="price" value="{{ $item->price }}" 
                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring focus:ring-bekas-dark">
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="tersedia" {{ $item->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="booking" {{ $item->status == 'booking' ? 'selected' : '' }}>Booking</option>
                    <option value="terjual" {{ $item->status == 'terjual' ? 'selected' : '' }}>Terjual</option>
                </select>
            </div>

            {{-- Tombol Update --}}
            <div class="text-right">
                <button class="bg-bekas-dark text-white px-6 py-3 rounded-lg hover:bg-bekas-light transition">
                    Update Barang
                </button>
            </div>

        </div>
    </form>

</div>
@endsection
