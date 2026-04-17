<nav class="bg-bekas-dark text-white py-4 px-6 md:px-12 flex justify-between items-center shadow-md sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <a href="{{ route('landing') }}" class="flex items-center gap-3">
            <div class="w-11 h-11 flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Bekaswit" class="w-full h-full object-contain">
            </div>
            <div class="leading-tight">
                <h1 class="font-bold text-xl tracking-wide">BEKASWIT</h1>
                <p class="text-[10px] text-gray-300 tracking-wider">Bekas Jadi Duwit</p>
            </div>
        </a>
    </div>

    <div class="hidden md:flex flex-1 max-w-xl mx-8 relative">
        <input type="text" placeholder="Cari barang Mahasiswa Malang" class="w-full py-2.5 px-4 rounded-lg text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-bekas-green">
        <button class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        </button>
    </div>

    <div class="flex items-center gap-4 md:gap-6 text-sm font-medium">
        <!-- Tombol Login dan Register Saja -->
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="text-white hover:text-bekas-green transition font-semibold">Masuk</a>
            <a href="{{ route('register') }}" class="bg-white text-bekas-dark hover:bg-bekas-green hover:text-white px-5 py-2 rounded-full font-bold shadow-sm transition-colors">Daftar</a>
        </div>

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