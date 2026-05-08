@extends('layouts.app')

@section('title', 'Barang Saya')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold text-gray-800">Barang Saya</h1>
    <p class="text-gray-500 mb-6">Kelola barang yang kamu jual</p>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Statistik --}}
    <div class="bg-gray-100 p-4 rounded-xl shadow-sm mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-green-900 text-white p-4 rounded-xl text-center">
                <p>Total Barang</p>
                <h2 class="text-xl font-bold">{{ $total }}</h2>
            </div>
            <div class="bg-yellow-300 p-4 rounded-xl text-center">
                <p>Tersedia</p>
                <h2 class="text-xl font-bold">{{ $tersedia }}</h2>
            </div>
            <div class="bg-green-200 p-4 rounded-xl text-center">
                <p>Booking</p>
                <h2 class="text-xl font-bold">{{ $booking }}</h2>
            </div>
            <div class="bg-gray-700 text-white p-4 rounded-xl text-center">
                <p>Terjual</p>
                <h2 class="text-xl font-bold">{{ $terjual }}</h2>
            </div>
        </div>
    </div>

    {{-- Grid --}}
    <div class="bg-gray-100 p-6 rounded-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

            @forelse($items as $item)
                <div class="bg-white rounded-xl p-3 shadow">

                    <div class="bg-gray-200 h-32 rounded-lg relative flex items-center justify-center">
                        @if($item->image)
                            <img src="{{ asset('images/' . $item->image) }}" 
                            alt="{{ $item->name }}" 
                            class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500">
                        @endif

                        <span class="absolute top-2 right-2 text-xs px-2 py-1 rounded-full 
                            {{ $item->status == 'terjual' ? 'bg-red-500 text-white' : 'bg-green-600 text-white' }}">
                            {{ ucfirst($item->status) }}
                        </span>

                        <span class="absolute bottom-2 left-2 text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                            {{ $item->category }}
                        </span>
                    </div>

                    <div class="mt-3">
                        {{-- Nama Barang --}}
                        <h3 class="text-sm font-semibold">{{ $item->name }}</h3>
                        <p class="text-sm font-bold">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">📍 {{ $item->location }}</p>

                        {{-- Tombol Edit --}}
                        <a href="{{ route('barang.edit', $item->id) }}"
                        class="text-blue-500 text-xs mt-2 block">
                        Edit
                        </a>

                        <a href="https://wa.me/6281234567890"
                           class="block mt-3 bg-bekas-dark text-white text-xs py-2 text-center rounded-lg">
                           Hubungi Penjual
                        </a>
                    </div>

                </div>
            @empty
                <p class="col-span-full text-center text-gray-400">Belum ada barang</p>
            @endforelse

        </div>
    </div>

</div>

{{-- Float Button --}}
<a href="{{ route('barang.jual') }}"
   class="fixed bottom-6 right-6 bg-yellow-300 w-14 h-14 flex items-center justify-center rounded-full shadow-lg text-2xl font-bold">
    +
</a>
@endsection