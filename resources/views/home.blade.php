@extends('layouts.app')

@section('title', 'Bekaswit - Beranda')

@section('content')
    <div class="px-6 md:px-12 pt-10 md:pt-16 pb-16 md:pb-28 flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto gap-8 text-center md:text-left">
        <div class="w-full md:w-1/2 space-y-6">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-bekas-dark leading-tight">
                BELI SEKEN, HEMAT,<br class="hidden md:block"> & SUSTAINABLE
            </h2>
            <p class="text-base md:text-lg text-gray-600">Marketplace barang bekas Mahasiswa Malang</p>
            <a href="#" class="inline-block bg-bekas-green text-white px-8 py-3.5 rounded-lg font-bold hover:bg-green-700 hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl w-full md:w-auto">
                JELAJAHI SEKARANG
            </a>
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 flex items-center justify-center">
            <img src="https://placehold.co/600x400/e2e8f0/64748b?text=Ilustrasi+Banner" class="w-[250px] md:w-[400px] h-auto drop-shadow-2xl hover:scale-105 transition-transform duration-500 rounded-2xl" alt="Ilustrasi Banner">
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10 -mt-10 md:-mt-20">
        <div class="bg-bekas-dark rounded-2xl shadow-2xl p-5 md:p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 border border-gray-700 backdrop-blur-sm bg-opacity-95">
            
            @php
                // Array data untuk menyederhanakan kode HTML form
                $filters = [
                    ['label' => 'Kategori', 'options' => ['📱 📦 🏷️ 👕 Semua Kategori', '💻 Elektronik', '🪑 Furniture', '👕 Fashion', '🎸 Hobi']],
                    ['label' => 'Kecamatan', 'options' => ['Lowokwaru, Klojen', 'Blimbing']],
                    ['label' => 'Kondisi', 'options' => ['Sangat baik', 'Minus pemakaian']],
                    ['label' => 'Harga', 'options' => ['Termurah', 'Termahal']],
                ];
            @endphp

            @foreach($filters as $filter)
                <div class="w-full relative">
                    <label class="block text-gray-300 text-xs font-semibold tracking-wider uppercase mb-2">{{ $filter['label'] }}</label>
                    <div class="relative">
                        <select class="w-full bg-white text-gray-800 text-sm font-medium py-3 pl-4 pr-10 rounded-lg focus:ring-2 focus:ring-bekas-green focus:border-bekas-green outline-none appearance-none transition-all shadow-sm cursor-pointer">
                            @foreach($filter['options'] as $option)
                                <option>{{ $option }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pt-20 pb-24">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            
            @forelse ($items as $item)
                <div class="group bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-gray-200 transition-all duration-300 flex flex-col h-full">
                    
                    <div class="w-full aspect-[4/3] bg-gray-50 rounded-xl relative mb-5 flex items-center justify-center overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                        
                        <span class="absolute top-3 right-3 {{ $item->status == 'terjual' ? 'bg-red-500' : 'bg-bekas-green' }} text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wide shadow-md backdrop-blur-sm bg-opacity-90">
                            {{ $item->status == 'terjual' ? 'Terjual' : 'Tersedia' }}
                        </span>
                    </div>

                    <h3 class="text-base font-semibold text-gray-800 line-clamp-2 leading-snug group-hover:text-bekas-green transition-colors">{{ $item->name }}</h3>
                    <p class="text-lg font-bold text-gray-900 mt-2 mb-3">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    
                    <div class="flex items-center text-xs text-gray-500 mb-5 mt-auto bg-gray-50 p-2 rounded-md">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                        <span class="truncate">{{ $item->location }}</span>
                    </div>

                    <a href="https://wa.me/6281234567890" target="_blank" class="w-full bg-bekas-dark text-white text-sm font-semibold py-2.5 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-800 focus:ring-4 focus:ring-gray-200 transition-all">
                        <svg viewBox="0 0 448 512" class="w-4 h-4 fill-current"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zM223.9 413.3c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 334.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 54.3 0 105.4 21.2 143.8 59.6 38.4 38.4 59.6 89.5 59.6 143.8 0 101.7-82.8 184.5-184.6 184.5zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                        Hubungi Penjual
                    </a>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <span class="text-5xl mb-4 grayscale opacity-50">🛒</span>
                    <p class="text-lg font-medium text-gray-500">Belum ada barang yang dijual saat ini.</p>
                    <p class="text-sm mt-1">Coba sesuaikan filter pencarianmu.</p>
                </div>
            @endforelse

        </div>
    </div>
@endsection