@extends('layouts.app')

@section('title', 'Bekaswit - Beranda')

@section('content')
    {{-- CSS Animasi --}}
    <style>
        /* State awal sebelum di-scroll */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
        }
        /* State ketika elemen masuk ke layar */
        .animate-on-scroll.is-visible {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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

    <div class="relative overflow-hidden bg-gray-50/50">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#1f2937 1px, transparent 1px); background-size: 32px 32px;"></div>
        
        <div class="px-4 sm:px-6 pt-16 md:pt-24 pb-24 md:pb-32 flex flex-col md:flex-row justify-between items-center max-w-6xl mx-auto gap-12 text-center md:text-left relative z-10">
            
            <div class="w-full md:w-1/2 space-y-6 md:space-y-8 pr-0 md:pr-4 animate-on-scroll">
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
            
            <div class="w-full md:w-1/2 mt-12 md:mt-0 flex items-center justify-center md:justify-end animate-on-scroll" style="animation-delay: 200ms;">
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
        <form action="{{ route('home') }}" method="GET" class="animate-on-scroll bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] p-3 md:p-4 border border-gray-100 flex flex-col gap-3">
            
            {{-- Filter Row --}}
            <div class="flex flex-col lg:flex-row items-stretch gap-3">
                <input type="hidden" name="search" value="{{ request('search') }}">
                @php
                    $filters = [
                        ['id' => 'kategori', 'label' => 'Kategori', 'icon' => 'grid', 'options' => ['Semua Kategori', 'Elektronik', 'Furniture', 'Fashion', 'Hobi']],
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
                    <button type="submit" class="w-full lg:w-auto h-full bg-bekas-green text-white px-8 py-3.5 rounded-xl flex justify-center items-center gap-2.5 transition-all duration-300 shadow-md hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-green-700/20 font-bold text-sm">
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

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.custom-dropdown').forEach(dd => closeDropdown(dd));
            }
        });
    </script>
        
    {{-- Catalog Product --}}
    <div id="katalog-produk" class="max-w-6xl mx-auto px-4 sm:px-6 pt-20 pb-24 scroll-mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            
            {{-- Product Cards --}}
           @forelse ($items as $item)
                <div class="animate-on-scroll" style="animation-delay: {{ ($loop->index % 4) * 150 }}ms;">
                    
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
                                
                                <form action="{{ route('cart.add') }}" method="POST" class="w-full relative z-20 add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <button type="submit" class="btn-action w-full bg-white border-2 border-gray-200 text-gray-700 text-sm font-bold py-2.5 rounded-xl flex items-center justify-center gap-2 hover:bg-bekas-green hover:border-bekas-green hover:text-white transition-all duration-300 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        +Keranjang
                                    </button>
                                </form>
                            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15 
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-on-scroll').forEach((el) => {
                observer.observe(el);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartForms = document.querySelectorAll('.add-to-cart-form');
            
            cartForms.forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault(); // Mencegah halaman reload

                    const btn = this.querySelector('button[type="submit"]');
                    const originalHtml = btn.innerHTML; // Simpan tampilan tombol asli

                    // Ubah tampilan tombol jadi "Loading"
                    btn.innerHTML = `<svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
                    btn.disabled = true;

                    try {
                        // Kirim data ke backend
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: new FormData(this),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest', // Tanda AJAX
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            // 1. Ambil data JSON dari respons Controller
                            const data = await response.json();

                            // 2. Update Badge Keranjang di Header secara Real-Time!
                            const cartBadge = document.getElementById('cart-badge');
                            if (cartBadge && data.cart_count) {
                                cartBadge.textContent = data.cart_count; // Ubah angkanya
                                cartBadge.classList.remove('hidden'); // Munculkan jika sebelumnya 0 (hidden)
                                
                                // (Opsional) Beri animasi denyut kecil pada badge saat angkanya berubah
                                cartBadge.classList.add('scale-125');
                                setTimeout(() => cartBadge.classList.remove('scale-125'), 300);
                            }

                            // 3. Sukses! Ubah warna tombol jadi hijau
                            btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Berhasil!`;
                            btn.classList.add('bg-bekas-green', 'text-white', 'border-bekas-green');
                            btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');

                            // Kembalikan tombol ke semula setelah 2 detik
                            setTimeout(() => {
                                btn.innerHTML = originalHtml;
                                btn.disabled = false;
                                btn.classList.remove('bg-bekas-green', 'text-white', 'border-bekas-green');
                                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200');
                            }, 2000);
                        } else {
                            if(response.status === 401) {
                                window.location.href = "{{ route('login') }}"; 
                                return;
                            }
                            throw new Error('Gagal');
                        }
                    } catch (error) {
                        btn.innerHTML = '❌ Gagal';
                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.disabled = false;
                        }, 2000);
                    }
                });
            });
        });
    </script>
@endsection