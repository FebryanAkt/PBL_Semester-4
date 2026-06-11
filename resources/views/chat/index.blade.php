@extends('layouts.app')

@section('title', 'Pesan - Bekaswit')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
            <h1 class="text-xl font-extrabold text-gray-800">Pesan Saya</h1>
        </div>
        
        {{-- Pembatas antar chat dipertegas dengan divide-gray-200 --}}
        <div class="divide-y divide-gray-200">
            @forelse($conversations as $conv)
                
                {{-- KOTAK CHAT (Ditambahkan 'group', border-l, dan hover:bg-green-50) --}}
                <a href="{{ route('chat.show', ['id' => $conv['user']->id, 'item_id' => optional($conv['item'])->id]) }}" 
                   class="group flex items-center gap-4 p-5 border-l-4 border-transparent hover:border-bekas-green hover:bg-green-50 transition-all duration-300 relative">
                    
                    {{-- Avatar Inisial (Berubah jadi full color saat div di-hover) --}}
                    <div class="w-14 h-14 rounded-full bg-bekas-green/10 flex items-center justify-center text-bekas-green font-bold text-xl shrink-0 group-hover:bg-bekas-green group-hover:text-white transition-colors duration-300">
                        {{ substr($conv['user']->name, 0, 1) }}
                    </div>
                    
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            {{-- Nama Pengguna (Berubah hijau saat di-hover) --}}
                            <h3 class="font-bold text-gray-900 truncate group-hover:text-bekas-green transition-colors duration-300">
                                {{ $conv['user']->name }}
                            </h3>
                            <span class="text-xs text-gray-500 whitespace-nowrap ml-2 font-medium">
                                {{ $conv['last_message']->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        @if($conv['item'])
                            <p class="text-[11px] text-bekas-green font-bold uppercase tracking-wider truncate mb-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                {{ $conv['item']->name }}
                            </p>
                        @endif
                        
                        {{-- Isi Pesan --}}
                        <p class="text-sm truncate {{ $conv['unread'] > 0 ? 'font-bold text-gray-900' : 'text-gray-500' }}">
                            {{ $conv['last_message']->sender_id == Auth::id() ? 'Anda: ' : '' }}{{ $conv['last_message']->message }}
                        </p>
                    </div>
                    
                    {{-- Badge Unread --}}
                    @if($conv['unread'] > 0)
                        <div class="w-6 h-6 bg-bekas-green rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0 shadow-sm">
                            {{ $conv['unread'] }}
                        </div>
                    @endif

                    {{-- Panah Indikator Hover (Hanya muncul saat disorot) --}}
                    <div class="opacity-0 group-hover:opacity-100 transform -translate-x-4 group-hover:translate-x-0 transition-all duration-300 text-bekas-green hidden sm:block ml-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </div>

                </a>
            @empty
                {{-- Tampilan Kosong Dipercantik --}}
                <div class="p-16 text-center text-gray-400 bg-gray-50/30">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <p class="text-lg font-semibold text-gray-500">Belum ada pesan masuk</p>
                    <p class="text-sm mt-1">Obrolan dengan penjual atau pembeli akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection