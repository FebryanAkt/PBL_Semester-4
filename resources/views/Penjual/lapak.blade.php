@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Lapak -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-green-700">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">Bergabung sejak {{ $user->created_at->translatedFormat('d F Y') }}</p>
                <p class="text-sm text-gray-500">Total Barang: {{ $items->count() }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('chat.show', ['id' => $user->id]) }}" 
                    class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">
                    Hubungi Penjual
                </a>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
                   Ikuti Toko
                </button>
            </div>
        </div>

        <!-- Produk dari Penjual -->
        <h3 class="text-xl font-bold text-gray-800 mb-6">Produk dari {{ $user->name }}</h3>

        <div id="katalog-produk" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            @forelse ($items as $item)
                <div class="product-card bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full relative">
                    
                    <!-- Gambar -->
                    <div class="w-full aspect-[4/3] bg-gray-100 rounded-xl relative mb-4 flex items-center justify-center overflow-hidden shrink-0">
                        @if($item->image)
                            <img src="{{ asset('images/' . $item->image) }}" 
                                 alt="{{ $item->name }}" 
                                 class="object-cover w-full h-full transition-transform duration-500 ease-out">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        @endif

                        <!-- Status -->
                        <span class="absolute top-2.5 left-2.5 
                                     {{ $item->status == 'terjual' ? 'bg-red-500' : ($item->status == 'booking' ? 'bg-yellow-500' : 'bg-green-600') }} 
                                     text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm z-20">
                            {{ strtoupper($item->status) }}
                        </span>
                    </div>

                    <!-- Info Produk -->
                    <div class="flex flex-col flex-grow px-1">
                        <span class="text-[10px] font-bold text-bekas-green uppercase tracking-wider mb-1.5">
                            {{ $item->category ?? 'Lainnya' }}
                        </span>

                        <h3 class="card-title text-base font-bold text-gray-800 line-clamp-2 leading-tight mb-3">
                            <a href="{{ route('produk.detail', ['id' => $item->id]) }}" class="hover:text-bekas-green transition-colors duration-300">
                                {{ $item->name }}
                            </a>
                        </h3>

                        <p class="text-lg font-black text-gray-900 leading-none mb-2">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </p>

                        <div class="flex items-center text-xs text-gray-500 mb-3">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            <span class="truncate font-medium">{{ $item->location }}</span>
                        </div>

                        <!-- Tombol Keranjang -->
                        <form action="{{ route('cart.add') }}" method="POST" class="w-full mb-2">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <button type="submit" class="w-full bg-white border-2 border-gray-200 text-gray-700 text-sm font-bold py-2.5 rounded-xl flex items-center justify-center gap-2 hover:bg-bekas-green hover:border-bekas-green hover:text-white transition-all duration-300">
                                <a href="{{ route('produk.detail', ['id' => $item->id]) }}">
                                    Detail Produk
                                </a>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <span class="text-5xl mb-4 grayscale opacity-50">🛒</span>
                    <p class="text-lg font-medium text-gray-500">Belum ada barang yang dijual saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection