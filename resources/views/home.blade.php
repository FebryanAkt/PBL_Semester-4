@extends('layouts.app')

@section('title', 'Bekaswit - Beranda')

@section('content')
    <!-- Hero Section -->
<div class="relative overflow-hidden bg-gray-50/50">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#1f2937 1px, transparent 1px); background-size: 32px 32px;"></div>
        
        <div class="px-4 sm:px-6 pt-16 md:pt-24 pb-24 md:pb-32 flex flex-col md:flex-row justify-between items-center max-w-6xl mx-auto gap-12 text-center md:text-left relative z-10">
            
            <div class="w-full md:w-1/2 space-y-6 md:space-y-8 pr-0 md:pr-4">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-bekas-green/10 text-bekas-green text-sm font-bold tracking-wide border border-bekas-green/20">
                    ✨ Marketplace Mahasiswa Malang
                </div>
                
                <h2 class="text-4xl md:text-5xl font-extrabold text-bekas-dark leading-[1.15] tracking-tight">
                    BELI SEKEN,<br class="hidden sm:block"> 
                    <span class="text-bekas-green relative inline-block">
                        HEMAT,
                        <svg class="absolute w-full h-3 -bottom-1 left-0 text-emerald-300/40" fill="currentColor" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5 L 100 10 L 0 10 Z"></path></svg>
                    </span> 
                    & SUSTAINABLE
                </h2>
                
                <p class="text-base md:text-lg text-gray-600 max-w-lg mx-auto md:mx-0 leading-relaxed font-medium">
                    Temukan berbagai barang bekas berkualitas dengan harga yang bersahabat.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 pt-2 justify-center md:justify-start">
                    <a href="#katalog-produk" class="group flex items-center justify-center gap-3 bg-bekas-dark text-white px-8 py-4 rounded-xl font-bold transition-all duration-300 shadow-lg hover:scale-105 hover:shadow-2xl hover:shadow-bekas-dark/40 w-full sm:w-auto">
                        JELAJAHI SEKARANG
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
            
            <div class="w-full md:w-1/2 mt-12 md:mt-0 flex items-center justify-center md:justify-end">
                
                <div class="w-full max-w-[480px] aspect-[4/3] md:aspect-[16/10] relative overflow-hidden rounded-2xl shadow-2xl border-[6px] border-white bg-white" id="banner-slider">
                    <div id="slider-track" class="flex w-full h-full transition-transform duration-700 ease-in-out">
                        @php
                            $banners = [
                                ['image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=1000&h=600&q=80', 'title' => 'Promo Elektronik', 'subtitle' => 'Laptop & Gadget Kuliah'],
                                ['image' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1000&h=600&q=80', 'title' => 'Furniture Kosan', 'subtitle' => 'Meja, Kursi & Nyaman'],
                                ['image' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=1000&h=600&q=80', 'title' => 'Thrift Fashion', 'subtitle' => 'Tampil Kece Tanpa Kere'],
                                ['image' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1000&h=600&q=80', 'title' => 'Alat Hobi', 'subtitle' => 'Musik, Olahraga & Fotografi']
                            ];
                        @endphp
                        
                        @foreach($banners as $banner)
                            <div class="slider-item w-full h-full flex-shrink-0 relative">
                                <img src="{{ $banner['image'] }}" class="w-full h-full object-cover transition-transform duration-1000 ease-out" alt="{{ $banner['title'] }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6 md:p-10">
                                    <h3 class="text-white font-extrabold text-2xl md:text-3xl tracking-tight mb-1.5">{{ $banner['title'] }}</h3>
                                    <p class="text-gray-200 font-medium text-sm md:text-base">{{ $banner['subtitle'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- ===== SEARCH & FILTER BAR ===== --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-30 -mt-8 md:-mt-12">
        <form action="{{ route('home') }}" method="GET" class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] p-3 md:p-4 border border-gray-100 flex flex-col gap-3">
            

            {{-- Filter Row --}}
            <div class="flex flex-col lg:flex-row items-stretch gap-3">
                <input type="hidden" name="search" value="{{ request('search') }}">
                @php
                    $filters = [
                        ['id' => 'kategori', 'label' => 'Kategori', 'icon' => 'grid', 'options' => ['Semua Kategori', '💻 Elektronik', '🪑 Furniture', '👕 Fashion', '🎸 Hobi']],
                        ['id' => 'kecamatan', 'label' => 'Kecamatan', 'icon' => 'map', 'options' => ['Semua Kecamatan', 'Lowokwaru', 'Klojen', 'Blimbing']],
                        ['id' => 'kondisi', 'label' => 'Kondisi', 'icon' => 'badge', 'options' => ['Semua Kondisi', 'Sangat Baik', 'Baik', 'Minus Pemakaian']],
                        ['id' => 'harga', 'label' => 'Harga', 'icon' => 'money', 'options' => ['Urutkan Harga', 'Termurah', 'Termahal']],
                    ];
                @endphp

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 w-full">
                    @foreach($filters as $fIdx => $filter)
                        <div class="relative custom-dropdown" data-dropdown="{{ $filter['id'] }}">
                            <input type="hidden" name="{{ $filter['id'] }}" id="input-{{ $filter['id'] }}" value="{{ request($filter['id'], $filter['options'][0]) }}">
                        {{-- Trigger Button --}}
                            <button type="button" 
                                class="dropdown-trigger w-full flex items-center gap-3 px-4 py-3 bg-gray-50 hover:bg-gray-200 border border-transparent rounded-xl transition-all duration-300 cursor-pointer"
                                onclick="toggleDropdown('{{ $filter['id'] }}')">
                                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white shadow-sm text-gray-500 transition-colors duration-200 shrink-0">
                                    @if($filter['icon'] == 'grid')
                                        <svg class="w-4.5 h-4.5 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    @elseif($filter['icon'] == 'map')
                                        <svg class="w-4.5 h-4.5 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    @elseif($filter['icon'] == 'badge')
                                        <svg class="w-4.5 h-4.5 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                    @else
                                        <svg class="w-4.5 h-4.5 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </div>
                                <div class="flex flex-col items-start min-w-0 flex-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-0.5">{{ $filter['label'] }}</span>
                                    <span class="dropdown-value text-sm font-semibold text-gray-700 truncate w-full text-left">{{ request($filter['id'], $filter['options'][0]) }}</span>
                                </div>
                                <svg class="dropdown-chevron w-4 h-4 text-gray-400 transition-transform duration-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div class="dropdown-menu hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-[0_12px_40px_rgba(0,0,0,0.12)] border border-gray-100 overflow-hidden z-50 opacity-0 -translate-y-2 transition-all duration-200">
                                @foreach($filter['options'] as $oIdx => $option)
                                    <button type="button"
                                        class="dropdown-option w-full px-4 py-3 text-left text-sm font-medium transition-all duration-200 flex items-center gap-3 {{ $oIdx === 0 ? 'text-bekas-green bg-bekas-green/5 font-semibold' : 'text-gray-600' }}"
                                        onclick="selectOption('{{ $filter['id'] }}', this, '{{ $option }}')">
                                        <span class="w-2 h-2 rounded-full {{ $oIdx === 0 ? 'bg-bekas-green' : 'bg-transparent' }} transition-colors duration-200 shrink-0"></span>
                                        {{ $option }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Search Button --}}
                <div class="flex-shrink-0">
                    <button type="submit" class="w-full lg:w-auto h-full bg-bekas-green text-white px-8 py-3.5 rounded-xl flex justify-center items-center gap-2.5 transition-all duration-300 shadow-md hover:scale-[1.03] focus:outline-none focus:ring-4 focus:ring-green-700/20 font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span>Cari</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Dropdown JavaScript --}}
    <script>
        function toggleDropdown(id) {
            const allDropdowns = document.querySelectorAll('.custom-dropdown');
            allDropdowns.forEach(dd => {
                if (dd.dataset.dropdown !== id) {
                    closeDropdown(dd);
                }
            });

            const dropdown = document.querySelector(`[data-dropdown="${id}"]`);
            const menu = dropdown.querySelector('.dropdown-menu');
            const chevron = dropdown.querySelector('.dropdown-chevron');
            const isOpen = !menu.classList.contains('hidden');

            if (isOpen) {
                closeDropdown(dropdown);
            } else {
                menu.classList.remove('hidden');
                requestAnimationFrame(() => {
                    menu.classList.remove('opacity-0', '-translate-y-2');
                    menu.classList.add('opacity-100', 'translate-y-0');
                });
                chevron.classList.add('rotate-180');
            }
        }

        function closeDropdown(dropdown) {
            const menu = dropdown.querySelector('.dropdown-menu');
            const chevron = dropdown.querySelector('.dropdown-chevron');
            menu.classList.add('opacity-0', '-translate-y-2');
            menu.classList.remove('opacity-100', 'translate-y-0');
            chevron.classList.remove('rotate-180');
            setTimeout(() => menu.classList.add('hidden'), 200);
        }

        function selectOption(id, el, value) {
            const dropdown = document.querySelector(`[data-dropdown="${id}"]`);
            const valueDisplay = dropdown.querySelector('.dropdown-value');
            valueDisplay.textContent = value;

            const hiddenInput = document.getElementById(`input-${id}`);
            if (hiddenInput) {
                hiddenInput.value = value;
            }

            // Update active state
            const options = dropdown.querySelectorAll('.dropdown-option');
            options.forEach(opt => {
                opt.classList.remove('text-bekas-green', 'bg-bekas-green/5', 'font-semibold');
                opt.classList.add('text-gray-600');
                opt.querySelector('span').classList.remove('bg-bekas-green');
                opt.querySelector('span').classList.add('bg-transparent');
            });
            el.classList.add('text-bekas-green', 'bg-bekas-green/5', 'font-semibold');
            el.classList.remove('text-gray-600');
            el.querySelector('span').classList.add('bg-bekas-green');
            el.querySelector('span').classList.remove('bg-transparent');

            closeDropdown(dropdown);
        }

        // Close dropdowns on click outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.custom-dropdown').forEach(dd => closeDropdown(dd));
            }
        });
    </script>
        {{-- Catalog Product --}}
        {{-- Style --}}
    <style>
        /* 1. Animasi dasar untuk card saat di-hover (Membesar & Bayangan) */
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* 2. PENGECUALIAN: Saat tombol 'btn-action' di-hover, kembalikan ukuran */
        .product-card:has(.btn-action:hover) {
            transform: scale(1);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
    {{-- kontainer --}}
    <div id="katalog-produk" class="max-w-6xl mx-auto px-4 sm:px-6 pt-20 pb-24 scroll-mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            
            {{-- Product Cards --}}
           @forelse ($items as $item)
                <div class="product-card bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full relative">

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

                        <span class="absolute top-2.5 left-2.5 {{ $item->status == 'terjual' ? 'bg-red-500' : 'bg-bekas-dark' }} text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm z-20">
                            {{ $item->status == 'terjual' ? 'Terjual' : 'Tersedia' }}
                        </span>
                    </div>

                    <div class="flex flex-col flex-grow px-1">
                        <div class="mb-1.5">
                            <span class="text-[10px] font-bold text-bekas-green uppercase tracking-wider">{{ $item->category ?? 'Lainnya' }}</span>
                        </div>
                        
                        <h3 class="card-title text-base font-bold text-gray-800 line-clamp-2 leading-tight mb-3">
                            <a href="{{ route('produk.detail', ['id' => $item->id]) }}" class="before:absolute before:inset-0 before:z-10 focus:outline-none">
                                <span class="relative z-20 hover:text-bekas-green transition-colors duration-300">
                                    {{ $item->name }}
                                </span>
                            </a>
                        </h3>
                        
                        <div class="mt-auto flex flex-col gap-3">
                            <p class="text-lg font-black text-gray-900 leading-none">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                                <span class="truncate font-medium">{{ $item->location }}</span>
                            </div>

                            <a href="https://wa.me/6281234567890" target="_blank" class="btn-action relative z-20 w-full bg-white border-2 border-gray-200 text-gray-700 text-sm font-bold py-2.5 rounded-xl flex items-center justify-center gap-2 hover:bg-bekas-green hover:border-bekas-green hover:text-white transition-all duration-300">
                                <svg viewBox="0 0 448 512" class="w-4 h-4 fill-current"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zM223.9 413.3c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 334.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 54.3 0 105.4 21.2 143.8 59.6 38.4 38.4 59.6 89.5 59.6 143.8 0 101.7-82.8 184.5-184.6 184.5zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                                Hubungi Penjual
                            </a>
                        </div>
                    </div>
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

    <!-- Script Banner Slider -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('slider-track');
            if (track) {
                const slides = track.querySelectorAll('.slider-item');
                if (slides.length > 1) {
                    let currentSlide = 0;
                    
                    setInterval(() => {
                        currentSlide = (currentSlide + 1) % slides.length;
                        track.style.transform = `translateX(-${currentSlide * 100}%)`;
                    }, 3000);
                }
            }
        });
    </script>
@endsection