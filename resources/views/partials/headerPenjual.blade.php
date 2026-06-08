<nav class="bg-bekas-dark text-white py-4 px-6 md:px-12 flex justify-between items-center shadow-md sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <a href="{{ route('barang.saya') }}" class="flex items-center gap-3 group relative transition-all duration-300">
            
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[130%] h-[160%] bg-white/10 blur-[15px] rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none z-0"></div>
            
            <div class="w-11 h-11 flex items-center justify-center transition-transform duration-300 group-hover:scale-105 relative z-10">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Bekaswit" class="w-full h-full object-contain">
            </div>
            
            <div class="leading-tight relative z-10">
                <h1 class="font-bold text-xl tracking-wide text-white">BEKASWIT</h1>
                <p class="text-[10px] text-gray-300 tracking-wider">Bekas Jadi Duwit</p>
            </div>
        </a>
    </div>

    <div class="flex items-center gap-4 md:gap-6 text-sm font-medium">
        
        @auth
            <div class="hidden md:flex items-center gap-6">

                <a href="{{ route('chat.index') }}" class="hover:text-bekas-green transition flex items-center" title="Pesan">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </a>
                @if(Auth::user()->isSeller())
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative flex justify-center">
                        <button type="button" class="hover:text-bekas-green transition flex items-center focus:outline-none cursor-pointer" title="Toko Saya">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute top-full left-1/2 -translate-x-1/2 mt-4 w-36 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden"
                             style="display: none;">

                            <a href="{{ route('barang.jual') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-bekas-green font-semibold transition-colors">
                                Jual Barang
                            </a>
                            <a href="{{ route('barang.saya') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-bekas-green font-semibold transition-colors border-t border-gray-50">
                                Barang Saya
                            </a>
                        </div>
                    </div>
                @endif
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
