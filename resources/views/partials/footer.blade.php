@php
    $marketplaceHomeUrl = Auth::check() && Auth::user()->isSeller()
        ? route('penjual.home')
        : route('home');
@endphp

<footer class="mt-auto bg-bekas-dark text-white">
    <div class="h-1 bg-bekas-green"></div>

    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-12 sm:grid-cols-2 lg:grid-cols-4">
        <div class="sm:col-span-2">
            <a href="{{ $marketplaceHomeUrl }}" class="inline-flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Bekaswit" class="h-12 w-12 object-contain">
                <div>
                    <p class="text-xl font-extrabold tracking-wide">BEKASWIT</p>
                    <p class="text-xs tracking-wider text-gray-300">Bekas Jadi Duwit</p>
                </div>
            </a>

            <p class="mt-5 max-w-md text-sm leading-6 text-gray-300">
                Marketplace barang bekas untuk mahasiswa Malang. Temukan barang berkualitas,
                hemat pengeluaran, dan bantu memperpanjang usia pakai barang.
            </p>
        </div>

        <div>
            <h2 class="text-sm font-bold uppercase tracking-wider text-white">Jelajahi</h2>
            <ul class="mt-5 space-y-3 text-sm text-gray-300">
                <li>
                    <a href="{{ $marketplaceHomeUrl }}" class="transition hover:text-bekas-green">Beranda</a>
                </li>
                <li>
                    <a href="{{ $marketplaceHomeUrl }}#katalog-produk" class="transition hover:text-bekas-green">Katalog Barang</a>
                </li>
                @auth
                    @if(Auth::user()->isSeller())
                        <li>
                            <a href="{{ route('barang.jual') }}" class="transition hover:text-bekas-green">Jual Barang</a>
                        </li>
                        <li>
                            <a href="{{ route('barang.saya') }}" class="transition hover:text-bekas-green">Barang Saya</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>

        <div>
            <h2 class="text-sm font-bold uppercase tracking-wider text-white">Akun</h2>
            <ul class="mt-5 space-y-3 text-sm text-gray-300">
                @auth
                    <li>
                        <a href="{{ route('profile.edit') }}" class="transition hover:text-bekas-green">Profil Saya</a>
                    </li>
                    <li>
                        <a href="{{ route('chat.index') }}" class="transition hover:text-bekas-green">Pesan</a>
                    </li>
                    <li>
                        <a href="{{ route('cart.index') }}" class="transition hover:text-bekas-green">Keranjang</a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}" class="transition hover:text-bekas-green">Masuk</a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="transition hover:text-bekas-green">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-6 py-5 text-xs text-gray-400 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} Bekaswit. Hak cipta dilindungi.</p>
            <p>Dibuat untuk jual beli barang bekas yang lebih berkelanjutan.</p>
        </div>
    </div>
</footer>
