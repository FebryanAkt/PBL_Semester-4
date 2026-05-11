<nav class="bg-bekas-dark text-white py-4 px-6 md:px-12 flex justify-between items-center shadow-md sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-90 transition-opacity">
            <div class="w-11 h-11 flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Bekaswit" class="w-full h-full object-contain">
            </div>
            <div class="leading-tight">
                <h1 class="font-bold text-xl tracking-wide">BEKASWIT</h1>
                <p class="text-[10px] text-gray-300 tracking-wider">Bekas Jadi Duwit</p>
            </div>
        </a>
    </div>

    <form action="{{ route('home') }}" method="GET" class="hidden md:flex flex-1 max-w-xl mx-8 relative group">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang Mahasiswa Malang..."
            class="w-full py-2.5 pl-5 pr-12 rounded-full bg-white/10 border border-white/20 text-sm text-white placeholder-gray-300 focus:outline-none focus:bg-white focus:text-gray-800 focus:placeholder-gray-400 focus:ring-4 focus:ring-bekas-green/50 transition-all duration-300 shadow-sm focus:shadow-lg">
        <button type="submit" class="absolute right-2 top-1.5 bottom-1.5 px-3 bg-white/10 group-focus-within:bg-bekas-green text-white rounded-full hover:bg-bekas-green transition-colors flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </button>
    </form>

    <div class="flex items-center gap-4 md:gap-6 text-sm font-medium">
        
        @auth
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('chat.index') }}" class="hover:text-bekas-green transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    Pesan
                </a>
                <a href="{{ route('barang.jual') }}" class="hover:text-bekas-green transition">Jual Barang</a>
                <a href="{{ route('barang.saya') }}" class="hover:text-bekas-green transition">Barang Saya</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('profile.edit') }}"
                    class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center cursor-pointer hover:bg-white transition overflow-hidden border border-transparent hover:border-gray-300"
                    title="Profil Anda">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profil"
                            class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                                clip-rule="evenodd" />
                        </svg>
                    @endif
                </a>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="text-xs font-bold px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-md transition-colors shadow-sm">
                        Keluar
                    </button>
                </form>
            </div>
        @endauth

        @guest
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-white hover:text-bekas-green transition font-semibold">Masuk</a>
                <a href="{{ route('register') }}" class="bg-white text-bekas-dark hover:bg-bekas-green hover:text-white px-5 py-2 rounded-full font-bold shadow-sm transition-colors">Daftar</a>
            </div>
        @endguest

        <button class="block md:hidden text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </div>
</nav>

<div class="block md:hidden bg-bekas-dark px-6 pb-4">
    <form action="{{ route('home') }}" method="GET" class="relative group">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang Mahasiswa Malang..."
            class="w-full py-2.5 pl-5 pr-12 rounded-full bg-white/10 border border-white/20 text-sm text-white placeholder-gray-300 focus:outline-none focus:bg-white focus:text-gray-800 focus:placeholder-gray-400 focus:ring-4 focus:ring-bekas-green/50 transition-all duration-300 shadow-sm focus:shadow-lg">
        <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-white/10 group-focus-within:bg-bekas-green text-white rounded-full hover:bg-bekas-green transition-colors flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </button>
    </form>
</div>