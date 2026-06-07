@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    <h2 class="text-3xl font-bold text-bekas-dark mb-8">Tambah Barang Yang Ingin Dijual</h2>

    <form action="/barang/jual_simpan" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h5 class="text-lg font-bold text-bekas-green mb-4 italic uppercase">unggah Foto</h5>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-5">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg h-64 flex flex-col items-center justify-center bg-gray-50 relative overflow-hidden">
                        <img id="preview" class="hidden w-full h-full object-cover absolute inset-0">
                        <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>

                        <span class="text-gray-500 font-semibold text-sm">Foto Utama</span>
                        <input type="file"name="foto_utama"accept="image/*"onchange="previewImage(event)"class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                </div>
                <div class="md:col-span-7 grid grid-cols-3 gap-2">
                   @for ($i = 1; $i <= 6; $i++)
                    <div class="border-2 border-dashed border-gray-300 rounded-lg h-28 flex flex-col items-center justify-center bg-gray-50 relative overflow-hidden">

                        <img id="previewTambahan{{ $i }}"
                            class="hidden w-full h-full object-cover absolute inset-0">

                        <span class="text-gray-400 text-2xl">+</span>

                        <span class="text-gray-400 text-xs uppercase">tambah</span>

                        <input type="file"
                            name="foto_tambahan[]"
                            accept="image/*"
                            onchange="previewTambahan(event, {{ $i }})"
                            class="absolute inset-0 opacity-0 cursor-pointer">

                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h5 class="text-lg font-bold text-bekas-dark mb-6">Detail Informasi</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang</label>
                    <input type="text" name="nama_barang" placeholder="  " class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kondisi</label>
                    <select name="kondisi" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none bg-white text-gray-500">
                        <option>Sangat Baik</option>
                        <option>Baik</option>
                        <option>Bekas</option>
                        <option>Minus Pemakaian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none bg-white text-gray-500">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('kategori') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga(Rp)</label>
                    <input type="number" name="harga" placeholder="Contoh: 150000" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Telepon (WhatsApp)</label>
                    <input type="text" name="nomor_telp" placeholder="Contoh: 081234567890" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi</label>
                    <select name="lokasi" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none bg-white text-gray-600">
                        <option value="Lowokwaru">Lowokwaru</option>
                        <option value="Klojen">Klojen</option>
                        <option value="Blimbing">Blimbing</option>
                        <option value="Kedungkandang">Kedungkandang</option>
                        <option value="Sukun">Sukun</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h5 class="text-lg font-bold text-bekas-dark mb-4">Deskripsi</h5>
            <textarea name="deskripsi" rows="4" placeholder="jelaskan kondisi, kelengkapan, dan alasan dijual" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-bekas-green focus:border-bekas-green outline-none"></textarea>
        </div>

        <div class="flex justify-end gap-4 mt-8 pb-10">
            <button type="button" onclick="history.back()" class="px-8 py-2 border-2 border-bekas-green text-bekas-green font-bold rounded-full hover:bg-bekas-green hover:text-white transition">BATAL</button>
            <button type="submit" class="px-8 py-2 bg-bekas-dark text-white font-bold rounded-full shadow-md hover:brightness-95 transition">Posting Barang</button>
        </div>
    </form>
</div>

<script>
function previewImage(event) {

    const input = event.target;
    const preview = document.getElementById('preview');

    if (input.files && input.files[0]) {

        const reader = new FileReader();

        reader.onload = function(e) {
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

        reader.onload = function(e) {

            preview.src = e.target.result;

            preview.classList.remove('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
