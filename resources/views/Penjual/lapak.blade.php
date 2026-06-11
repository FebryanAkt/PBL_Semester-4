@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8 mb-10 border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <h2 class="text-3xl font-extrabold text-bekas-dark mb-1">{{ $user->name }}</h2>
                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Bergabung sejak {{ $user->created_at->translatedFormat('F Y') }}
                    </span>
                    <span class="hidden sm:block text-gray-300">•</span>
                    <span class="flex items-center gap-1 font-semibold text-bekas-green">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        {{ $items->count() }} Barang Dijual
                    </span>
                </div>
            </div>
            
            <div class="flex w-full md:w-auto gap-3">
                <a href="{{ route('chat.show', ['id' => $user->id]) }}" 
                    class="flex-1 md:flex-none flex items-center justify-center gap-2 bg-bekas-green text-white px-6 py-3 rounded-xl shadow-md hover:bg-green-700 hover:shadow-lg transition-all duration-300 font-bold active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    Chat Penjual
                </a>
            </div>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            Katalog Produk <span class="text-bekas-green">{{ $user->name }}</span>
        </h3>

        <div id="katalog-produk" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($items as $item)
                
                {{-- KARTU PRODUK --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full hover:shadow-lg transition-all group">
                    
                    <div class="relative aspect-[4/3] w-full bg-gray-100 shrink-0 overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('images/' . $item->image) }}" 
                                 alt="{{ $item->name }}" 
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-300">📦</div>
                        @endif

                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-md text-[10px] font-bold text-white uppercase tracking-wider shadow-sm 
                            {{ $item->status == 'terjual' ? 'bg-red-500' : ($item->status == 'booking' ? 'bg-yellow-500' : 'bg-green-500') }}">
                            {{ $item->status }}
                        </span>

                        <span class="absolute bottom-3 left-3 px-2.5 py-1 rounded-md text-[10px] font-bold text-bekas-dark bg-white/90 backdrop-blur-sm shadow-sm uppercase tracking-wider">
                            {{ $item->category ?? 'Lainnya' }}
                        </span>
                    </div>

                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-base font-bold text-gray-800 line-clamp-2 leading-tight mb-2 flex-grow" title="{{ $item->name }}">
                            <a href="{{ route('produk.detail', ['id' => $item->id]) }}" class="hover:text-bekas-green transition-colors duration-300 focus:outline-none">
                                {{ $item->name }}
                            </a>
                        </h3>

                        <p class="text-lg font-black text-bekas-green mb-3">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </p>

                        <div class="flex items-center text-xs text-gray-500 mb-4">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            <span class="truncate">{{ $item->location }}</span>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-50">
                            <a href="{{ route('produk.detail', ['id' => $item->id]) }}" class="w-full bg-white border-2 border-gray-200 text-gray-700 text-sm font-bold py-2.5 rounded-xl flex items-center justify-center gap-2 hover:bg-bekas-green hover:border-bekas-green hover:text-white transition-all duration-300 shadow-sm active:scale-95 group/btn">
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail Produk
                            </a>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-gray-400 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <span class="text-6xl mb-4 grayscale opacity-40">📦</span>
                    <p class="text-xl font-bold text-gray-500 mb-1">Toko Masih Kosong</p>
                    <p class="text-sm font-medium">Penjual ini belum menambahkan barang dagangan apa pun.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection