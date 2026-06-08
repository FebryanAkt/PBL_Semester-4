@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-bekas-green text-green-700 p-4 rounded shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-6 md:gap-8">
            
            <div class="w-full md:w-1/3 lg:w-1/4 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm p-6 flex flex-col items-center text-center border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 mb-6">{{ $user->university ?? 'Belum ada universitas' }}</p>
                    
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="w-full flex flex-col items-center">
                        @csrf
                        @method('PUT')
                        
                        <div class="relative mb-6 group cursor-pointer" onclick="document.getElementById('avatar-input').click();">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-50 bg-gray-100 flex items-center justify-center relative">
                                @if($user->avatar)
                                    <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <svg id="avatar-preview-icon" class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <img id="avatar-preview" src="" class="w-full h-full object-cover hidden">
                                @endif
                                
                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="previewImage(event)">

                        <button type="submit" id="btn-upload" class="w-full bg-bekas-green hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm mb-4">
                            Simpan Foto Baru
                        </button>
                    </form>
                    
                    <div class="bg-blue-50 text-blue-600 rounded-lg p-3 text-xs w-full">
                        Unggah avatar baru. Resolusi maksimal 2MB. (Format: JPG, PNG).
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-100 w-full text-xs text-gray-400">
                        Bergabung sejak: {{ $user->created_at->translatedFormat('d F Y') }}
                    </div>
                </div>
            </div>

            <div class="w-full md:w-2/3 lg:w-3/4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    
                    <div class="px-8 pt-8 pb-4 border-b border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profil</h2>
                        <div class="flex gap-6 text-sm font-medium">
                            <span class="text-bekas-green border-b-2 border-bekas-green pb-2">Informasi Akun</span>
                        </div>
                    </div>

                    <div class="p-8">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent text-gray-800">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Asal Universitas</label>
                                    <input type="text" name="university" value="{{ old('university', $user->university) }}" placeholder="Contoh: Universitas Brawijaya" 
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent text-gray-800">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP / WhatsApp</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">+62</span>
                                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="81234567890" 
                                            class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent text-gray-800">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Pos-el</label>
                                    <input type="email" value="{{ $user->email }}" disabled 
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed">
                                </div>
                            </div>

                            <div class="mb-8">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (Kos/Rumah)</label>
                                <textarea name="address" rows="3" placeholder="Masukkan alamat lengkapmu di sini..."
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent text-gray-800 resize-y">{{ old('address', $user->address) }}</textarea>
                            </div>

                            <div>
                                <button type="submit" class="bg-bekas-green hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-4 focus:ring-green-700/30">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();
        
        reader.onload = function() {
            const preview = document.getElementById('avatar-preview');
            const icon = document.getElementById('avatar-preview-icon');
            
            preview.src = reader.result;
            preview.classList.remove('hidden');
            
            if(icon) {
                icon.classList.add('hidden');
            }
        };
        
        if(input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection