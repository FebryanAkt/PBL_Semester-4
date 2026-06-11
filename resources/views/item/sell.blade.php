@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-10 px-4">
        <h2 class="text-3xl font-bold text-bekas-dark mb-8">Tambah Barang Yang Ingin Dijual</h2>
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl shadow-sm">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <h4 class="text-sm font-bold text-red-800">Periksa Kembali Isian Form:</h4>
                </div>
                <ul class="list-disc list-inside text-xs text-red-700 space-y-1 ml-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/item/jual_simpan" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h5 class="text-lg font-bold text-bekas-green mb-4 italic uppercase">Unggah Foto</h5>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">

                    {{-- KOTAK 1: FOTO UTAMA --}}
                    <div class="md:col-span-5">
                        <div
                            class="group border-2 border-dashed border-gray-300 rounded-xl h-64 flex flex-col items-center justify-center bg-gray-50 relative overflow-hidden transition-all duration-300 hover:scale-[1.02] hover:border-bekas-green hover:shadow-md hover:bg-green-50/30 cursor-pointer">

                            <img id="preview" class="hidden w-full h-full object-cover absolute inset-0 z-10">

                            {{-- Ikon & Teks (Berubah warna saat div di-hover) --}}
                            <svg class="w-12 h-12 text-gray-400 mb-2 transition-colors duration-300 group-hover:text-bekas-green"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span
                                class="text-gray-500 font-semibold text-sm transition-colors duration-300 group-hover:text-bekas-green">Foto
                                Utama</span>

                            {{-- Input File (Z-index tertinggi agar selalu bisa diklik) --}}
                            <input type="file" name="foto_utama" accept="image/*" onchange="previewImage(event)"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                        </div>
                    </div>

                    {{-- KOTAK 2: FOTO TAMBAHAN --}}
                    <div class="md:col-span-7 grid grid-cols-3 gap-3">
                        @for ($i = 1; $i <= 6; $i++)
                            <div
                                class="group border-2 border-dashed border-gray-300 rounded-xl h-28 flex flex-col items-center justify-center bg-gray-50 relative overflow-hidden transition-all duration-300 hover:scale-105 hover:border-bekas-green hover:shadow-md hover:bg-green-50/30 cursor-pointer">

                                <img id="previewTambahan{{ $i }}"
                                    class="hidden w-full h-full object-cover absolute inset-0 z-10">

                                {{-- Ikon & Teks (Berubah warna saat div di-hover) --}}
                                <span
                                    class="text-gray-400 text-2xl transition-colors duration-300 group-hover:text-bekas-green">+</span>
                                <span
                                    class="text-gray-400 text-xs uppercase font-medium transition-colors duration-300 group-hover:text-bekas-green">Tambah</span>

                                {{-- Input File --}}
                                <input type="file" name="foto_tambahan[]" accept="image/*"
                                    onchange="previewTambahan(event, {{ $i }})"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                            </div>
                        @endfor
                    </div>

                </div>
            </div>

            {{-- BLOK DETAIL INFORMASI --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h5 class="text-lg font-bold text-bekas-dark mb-6">Detail Informasi</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang</label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                            placeholder="Contoh: Jaket Bomber Eiger (Minimal 5 karakter)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kondisi</label>
                        <select name="kondisi"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none bg-white text-gray-600">
                            <option value="Sangat Baik" {{ old('kondisi') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik
                            </option>
                            <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Bekas" {{ old('kondisi') == 'Bekas' ? 'selected' : '' }}>Bekas</option>
                            <option value="Cacat Pemakaian" {{ old('kondisi') == 'Cacat Pemakaian' ? 'selected' : '' }}>Cacat
                                Pemakaian</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                        <select name="kategori"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none bg-white text-gray-600">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('kategori') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" value="{{ old('harga') }}"
                            placeholder="Contoh: 150000 (Hanya angka, tanpa titik/koma)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon (WhatsApp)</label>
                        <input type="text" name="nomor_telp" value="{{ old('nomor_telp') }}"
                            placeholder="Contoh: 081234567890 (Minimal 10 digit)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Stok Barang</label>
                        <input type="number" name="stock" min="1" value="{{ old('stock', 1) }}" placeholder="Minimal 1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none placeholder-gray-400">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi Pengiriman/COD</label>
                        <select name="lokasi"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none bg-white text-gray-600">
                            <option value="Lowokwaru" {{ old('lokasi') == 'Lowokwaru' ? 'selected' : '' }}>Lowokwaru</option>
                            <option value="Klojen" {{ old('lokasi') == 'Klojen' ? 'selected' : '' }}>Klojen</option>
                            <option value="Blimbing" {{ old('lokasi') == 'Blimbing' ? 'selected' : '' }}>Blimbing</option>
                            <option value="Kedungkandang" {{ old('lokasi') == 'Kedungkandang' ? 'selected' : '' }}>
                                Kedungkandang</option>
                            <option value="Sukun" {{ old('lokasi') == 'Sukun' ? 'selected' : '' }}>Sukun</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- BLOK DESKRIPSI --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h5 class="text-lg font-bold text-bekas-dark mb-4">Deskripsi</h5>
                <textarea name="deskripsi" rows="4"
                    placeholder="Sebutkan detail kelengkapan, lama pemakaian, dan apakah ada minus/kerusakan kecil agar pembeli tidak kecewa nantinya."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none placeholder-gray-400">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex justify-end gap-4 mt-8 pb-10">
                <button type="button" onclick="history.back()"
                    class="px-8 py-2 border-2 border-bekas-green text-bekas-green font-bold rounded-full hover:bg-bekas-green hover:text-white transition">BATAL</button>
                <button type="submit"
                    class="px-8 py-2 bg-bekas-dark text-white font-bold rounded-full shadow-md hover:brightness-95 transition">Posting
                    Barang</button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {

            const input = event.target;
            const preview = document.getElementById('preview');

            if (input.files && input.files[0]) {

                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        function previewTambahan(event, index) {

            const input = event.target;

            const preview = document.getElementById('previewTambahan' + index);

            if (input.files && input.files[0]) {

                const reader = new FileReader();

                reader.onload = function (e) {

                    preview.src = e.target.result;

                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection