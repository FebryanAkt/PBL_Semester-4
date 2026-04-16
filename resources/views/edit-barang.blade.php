@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold mb-6">Edit Produk</h1>

    <form action="{{ route('barang.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-gray-100 p-6 rounded-xl space-y-4">

            <input type="text" name="name" value="{{ $item->name }}" class="w-full p-3 rounded-lg">

            <input type="text" name="category" value="{{ $item->category }}" class="w-full p-3 rounded-lg">

            <textarea name="description" class="w-full p-3 rounded-lg">
                {{ $item->description }}
            </textarea>

            <input type="text" name="tags" value="{{ $item->tags }}" class="w-full p-3 rounded-lg">

            <input type="file" name="image">

            <input type="number" name="price" value="{{ $item->price }}" class="w-full p-3 rounded-lg">

            <button class="bg-bekas-dark text-white px-6 py-3 rounded-lg">
                Update Barang
            </button>

        </div>
    </form>

</div>
@endsection