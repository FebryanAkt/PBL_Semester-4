@extends('layouts.guest')

@section('title', 'Bekaswit - Beli Seken, Hemat & Sustainable')

@section('content')
    <div class="px-6 md:px-12 pt-10 md:pt-16 pb-16 md:pb-28 flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto gap-8 text-center md:text-left">
        <div class="w-full md:w-1/2 space-y-6">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-bekas-dark leading-tight">
                BELI SEKEN, HEMAT,<br class="hidden md:block"> & SUSTAINABLE
            </h2>
            <p class="text-base md:text-lg text-gray-600">Marketplace barang bekas Mahasiswa Malang</p>
            <a href="#katalog-produk" class="inline-block bg-bekas-green text-white px-8 py-3.5 rounded-lg font-bold hover:bg-green-700 hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl w-full md:w-auto">
                JELAJAHI SEKARANG
            </a>
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0 flex items-center justify-center">
            <!-- Diganti bentuk persegi panjang layaknya banner promo marketplace (aspect-[2/1] agar proporsional) -->
            <div class="w-full max-w-[480px] aspect-[2/1] md:aspect-[21/9] relative overflow-hidden rounded-2xl drop-shadow-2xl group" id="banner-slider">
                <!-- Wrapper track flex untuk animasi geser/slide ke samping -->
                <div id="slider-track" class="flex w-full h-full transition-transform duration-[800ms] ease-in-out">
                    @php
                        // Daftar ilustrasi banner promo per kategori menggunakan gambar asli
                        $banners = [
                            [
                                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=800&h=400&q=80',
                                'title' => 'Promo Elektronik',
                                'subtitle' => 'Laptop & Gadget untuk Kuliah'
                            ],
                            [
                                'image' => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=800&h=400&q=80',
                                'title' => 'Furniture Kosan',
                                'subtitle' => 'Meja, Kursi & Nyaman'
                            ],
                            [
                                'image' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=800&h=400&q=80',
                                'title' => 'Thrift Fashion',
                                'subtitle' => 'Tampil Kece Tanpa Bikin Kere'
                            ],
                            [
                                'image' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=800&h=400&q=80',
                                'title' => 'Alat Hobi',
                                'subtitle' => 'Musik, Olahraga & Fotografi'
                            ]
                        ];
                    @endphp
                    
                    @foreach($banners as $banner)
                        <!-- Masing-masing slide mengambil ruang penuh 100% -->
                        <div class="slider-item w-full h-full flex-shrink-0 relative">
                            <img src="{{ $banner['image'] }}" class="w-full h-full object-cover rounded-2xl" alt="{{ $banner['title'] }}">
                            <!-- Overlay Text agar nuansa promosinya terlihat jelas di atas gambar -->
                            <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent rounded-2xl flex flex-col justify-center items-start p-6 lg:p-8">
                                <h3 class="text-white font-extrabold text-xl md:text-2xl mb-1 md:mb-2 shadow-sm">{{ $banner['title'] }}</h3>
                                <p class="text-gray-100 text-xs md:text-sm font-medium w-2/3 drop-shadow-md">{{ $banner['subtitle'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10 -mt-8 md:-mt-14">
        <!-- Floating Filter Panel bergaya modern / Airbnb style -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] p-2 md:p-3 border border-gray-100 flex flex-col lg:flex-row items-center gap-2">
            
            @php
                $filters = [
                    ['label' => 'Kategori', 'options' => ['Semua Kategori', '💻 Elektronik', '🪑 Furniture', '👕 Fashion', '🎸 Hobi']],
                    ['label' => 'Kecamatan', 'options' => ['Lowokwaru, Klojen', 'Blimbing']],
                    ['label' => 'Kondisi', 'options' => ['Sangat baik', 'Minus pemakaian']],
                    ['label' => 'Harga', 'options' => ['Termurah', 'Termahal']],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 w-full divide-y sm:divide-y-0 sm:divide-x divide-gray-100 lg:divide-x">
                @foreach($filters as $filter)
                    <div class="relative group px-1">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                            <!-- Ikon dinamis berdasarkan label filter -->
                            @if($filter['label'] == 'Kategori')
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-bekas-green transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            @elseif($filter['label'] == 'Kecamatan')
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-bekas-green transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            @elseif($filter['label'] == 'Kondisi')
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-bekas-green transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            @else
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-bekas-green transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                        <div class="flex flex-col pt-3 pb-2 pl-12 pr-4 hover:bg-gray-50 rounded-2xl transition-colors cursor-pointer relative">
                            <label class="text-xs font-extrabold text-gray-800 uppercase tracking-wider cursor-pointer mb-1">{{ $filter['label'] }}</label>
                            <select class="w-full bg-transparent text-gray-500 text-sm font-medium outline-none appearance-none cursor-pointer truncate">
                                @foreach($filter['options'] as $option)
                                    <option>{{ $option }}</option>
                                @endforeach
                            </select>
                            <!-- Ikon Panah Dropdown -->
                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="w-full lg:w-auto px-3 pb-3 pt-1 lg:p-0 flex-shrink-0">
                <button class="w-full lg:w-16 lg:h-16 bg-bekas-green text-white py-3 lg:py-0 rounded-2xl lg:rounded-full flex justify-center items-center hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-green-700/30">
                    <svg class="w-6 h-6 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span class="lg:hidden font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Cari Barang
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Destinasi scroll diarahkan langsung ke daftar barang/item -->
    <div id="katalog-produk" class="max-w-7xl mx-auto px-6 pt-20 pb-24 scroll-mt-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            
            @forelse ($items as $item)
                {{-- Hapus link gaib sebelumnya, pastikan div tetap memiliki relative --}}
                <div class="group bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-gray-200 transition-all duration-300 flex flex-col h-full relative">
                    
                    {{-- Bungkus Gambar --}}
                    <div class="w-full aspect-[4/3] bg-gray-50 rounded-xl relative mb-4 flex items-center justify-center overflow-hidden shrink-0">
                        @if($item->image)
                            <img src="{{ asset('images/' . $item->image) }}" 
                            alt="{{ $item->name }}" 
                            class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                        
                        {{-- Tambahkan z-20 agar badge tidak tertutup area klik --}}
                        <span class="absolute top-3 right-3 {{ $item->status == 'terjual' ? 'bg-red-500' : 'bg-bekas-green' }} text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wide shadow-md z-20">
                            {{ $item->status == 'terjual' ? 'Terjual' : 'Tersedia' }}
                        </span>
                    </div>

                    {{-- Bungkus Konten Teks agar rapi ke bawah --}}
                    <div class="flex flex-col flex-grow">
                        {{-- PERBAIKAN DI SINI: Gunakan before:absolute pada tag <a> di dalam judul --}}
                        <h3 class="text-base font-semibold text-gray-800 line-clamp-2 leading-snug group-hover:text-bekas-green transition-colors mb-1">
                            <a href="{{ route('produk.detail', ['id' => $item->id]) }}" class="before:absolute before:inset-0 before:z-10 focus:outline-none">
                                {{ $item->name }}
                            </a>
                        </h3>
                        
                        <p class="text-lg font-bold text-gray-900 mb-4">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        
                        {{-- Info Lokasi --}}
                        <div class="flex items-center text-xs text-gray-500 mb-4 mt-auto bg-gray-50 p-2 rounded-md relative z-20">
                            <svg class="w-4 h-4 mr-1.5 text-gray-400" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                            <span class="truncate">{{ $item->location }}</span>
                        </div>

                        {{-- Tombol WA --}}
                        <a href="https://wa.me/6281234567890" target="_blank" class="relative z-20 w-full bg-bekas-dark text-white text-sm font-semibold py-2.5 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-800 transition-all">
                            <svg viewBox="0 0 448 512" class="w-4 h-4 fill-current"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zM223.9 413.3c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 334.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 54.3 0 105.4 21.2 143.8 59.6 38.4 38.4 59.6 89.5 59.6 143.8 0 101.7-82.8 184.5-184.6 184.5zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                            Hubungi Penjual
                        </a>
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
                        // Geser ke slide berikutnya
                        currentSlide = (currentSlide + 1) % slides.length;
                        
                        // Set style transform translateX berdasarkan indeks slide
                        track.style.transform = `translateX(-${currentSlide * 100}%)`;
                    }, 3000); // Ganti slide otomatis setiap 3 detik
                }
            }
        });
    </script>
@endsection