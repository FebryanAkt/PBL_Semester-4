<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bekaswit - Beli Seken, Hemat & Sustainable</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bekas-dark': '#133045', /* Warna Biru Dongker Navbar */
                        'bekas-green': '#3a7d44', /* Warna Hijau Tombol */
                        'bekas-bg': '#e8f1f5',   /* Warna Background Body */
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bekas-bg font-sans antialiased text-gray-800">

    <nav class="bg-bekas-dark text-white py-4 px-6 md:px-12 flex justify-between items-center shadow-md sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Bekaswit" class="w-full h-full object-contain">
            </div>
            <div class="leading-tight">
                <h1 class="font-bold text-xl tracking-wide">BEKASWIT</h1>
                <p class="text-[10px] text-gray-300 tracking-wider">Bekas Jadi Duit</p>
            </div>
        </div>

        <div class="hidden md:flex flex-1 max-w-xl mx-8 relative">
            <input type="text" placeholder="Cari barang Mahasiswa Malang" class="w-full py-2.5 px-4 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-bekas-green">
            <button class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            </button>
        </div>

        <div class="flex items-center gap-4 md:gap-6 text-sm font-medium">
            <div class="hidden md:flex items-center gap-6">
                <a href="#" class="hover:text-bekas-green transition">Home</a>
                <a href="#" class="hover:text-bekas-green transition">Jual Barang</a>
                <a href="#" class="hover:text-bekas-green transition">Barang Saya</a>
            </div>
            <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center cursor-pointer hover:bg-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" /></svg>
            </div>
            <!-- Ikon menu garis tiga khusus HP -->
            <button class="block md:hidden text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>
        </div>
    </nav>

    <div class="block md:hidden bg-bekas-dark px-6 pb-4">
        <div class="relative">
            <input type="text" placeholder="Cari barang Mahasiswa Malang" class="w-full py-2.5 px-4 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-bekas-green">
            <button class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            </button>
        </div>
    </div>

    <div class="px-6 md:px-12 pt-10 md:pt-16 pb-12 md:pb-28 flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto gap-8 text-center md:text-left">
        <div class="w-full md:w-1/2">
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-bekas-dark mb-4 leading-tight">
                BELI SEKEN, HEMAT,<br class="hidden md:block">& SUSTAINABLE
            </h2>
            <p class="text-base md:text-lg text-gray-700 mb-8">Marketplace barang bekas Mahasiswa Malang</p>
            <a href="#" class="inline-block bg-bekas-green text-white px-8 py-3 rounded-lg font-bold hover:bg-green-700 transition shadow-lg w-full md:w-auto">
                JELAJAHI SEKARANG
            </a>
        </div>
        <div class="w-full md:w-1/2 mt-6 md:mt-0">
            <div class="w-full flex items-center justify-center">
                <img src="{{ asset('images/hero.png') }}" class="w-[250px] md:w-[400px] h-auto drop-shadow-2xl" alt="Ilustrasi Banner">
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10 -mt-6 md:-mt-16">
        <div class="bg-bekas-dark rounded-xl shadow-xl p-4 md:p-6 flex flex-col md:flex-row justify-between gap-4 md:gap-4 border border-gray-700">
            <div class="flex-1">
                <label class="block text-white text-sm mb-2">Kategori</label>
                <div class="bg-white rounded-md flex justify-between items-center px-2 py-1.5 h-[38px] text-gray-600">
                    <button class="group flex items-center justify-center gap-1 px-1.5 py-0.5 hover:bg-gray-100 rounded focus:outline-none focus:bg-gray-200 transition-all" title="Elektronik">
                        <span class="text-base">💻</span>
                        <span class="text-xs font-medium text-gray-800 hidden group-focus:block group-hover:block">Elektronik</span>
                    </button>
                    <button class="group flex items-center justify-center gap-1 px-1.5 py-0.5 hover:bg-gray-100 rounded focus:outline-none focus:bg-gray-200 transition-all" title="Furniture">
                        <span class="text-base">🪑</span>
                        <span class="text-xs font-medium text-gray-800 hidden group-focus:block group-hover:block">Furniture</span>
                    </button>
                    <button class="group flex items-center justify-center gap-1 px-1.5 py-0.5 hover:bg-gray-100 rounded focus:outline-none focus:bg-gray-200 transition-all" title="Fashion">
                        <span class="text-base">👕</span>
                        <span class="text-xs font-medium text-gray-800 hidden group-focus:block group-hover:block">Fashion</span>
                    </button>
                    <button class="group flex items-center justify-center gap-1 px-1.5 py-0.5 hover:bg-gray-100 rounded focus:outline-none focus:bg-gray-200 transition-all" title="Hobi">
                        <span class="text-base">🎸</span>
                        <span class="text-xs font-medium text-gray-800 hidden group-focus:block group-hover:block">Hobi</span>
                    </button>
                </div>
            </div>
            <div class="flex-1">
                <label class="block text-white text-sm mb-2">Kecamatan</label>
                <select class="w-full bg-white text-gray-800 text-sm font-medium py-2 px-3 rounded-md focus:outline-none">
                    <option>Lowokwaru, Klojen</option>
                    <option>Blimbing</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-white text-sm mb-2">Kondisi</label>
                <select class="w-full bg-white text-gray-800 text-sm font-medium py-2 px-3 rounded-md focus:outline-none">
                    <option>Sangat baik</option>
                    <option>Minus pemakaian</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-white text-sm mb-2">Harga</label>
                <select class="w-full bg-white text-gray-800 text-sm font-medium py-2 px-3 rounded-md focus:outline-none">
                    <option>Termurah</option>
                    <option>Termahal</option>
                </select>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pt-16 pb-24">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            
            @forelse ($items as $item)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition flex flex-col h-full">
                    <div class="w-full h-40 bg-gray-200 rounded-lg relative mb-4 flex items-center justify-center overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="object-cover w-full h-full">
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                        
                        @if($item->status == 'terjual')
                            <span class="absolute bottom-2 right-2 bg-red-600 text-white text-[10px] font-bold px-3 py-1 rounded-full">TERJUAL</span>
                        @else
                            <span class="absolute bottom-2 right-2 bg-bekas-green text-white text-[10px] font-bold px-3 py-1 rounded-full">Tersedia</span>
                        @endif
                    </div>

                    <h3 class="text-md font-semibold text-gray-800 line-clamp-1">{{ $item->name }}</h3>
                    <p class="text-sm font-bold text-gray-900 mt-1 mb-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    
                    <div class="flex items-center text-xs text-gray-500 mb-4 mt-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 mr-1"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                        {{ $item->location }}
                    </div>

                    <a href="https://wa.me/6281234567890" target="_blank" class="w-full bg-bekas-dark text-white text-sm font-semibold py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-[#0c1f2e] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-4 h-4 fill-current"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zM223.9 413.3c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 334.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 54.3 0 105.4 21.2 143.8 59.6 38.4 38.4 59.6 89.5 59.6 143.8 0 101.7-82.8 184.5-184.6 184.5zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                        Hubungi Penjual
                    </a>
                </div>
            @empty
                <div class="col-span-full py-16 flex flex-col items-center justify-center text-gray-500">
                    <span class="text-4xl mb-4">🛒</span>
                    <p class="text-lg">Belum ada barang yang dijual saat ini.</p>
                </div>
            @endforelse

        </div>
    </div>

</body>
</html>