@extends('layouts.app')

@section('title', 'Pesan - Bekaswit')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h1 class="text-xl font-bold text-gray-800">Pesan Saya</h1>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($conversations as $conv)
                <a href="{{ route('chat.show', ['id' => $conv['user']->id, 'item_id' => optional($conv['item'])->id]) }}" class="flex items-center gap-4 p-5 hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-bekas-green/10 flex items-center justify-center text-bekas-green font-bold text-lg shrink-0">
                        {{ substr($conv['user']->name, 0, 1) }}
                    </div>
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-gray-900 truncate">{{ $conv['user']->name }}</h3>
                            <span class="text-xs text-gray-500 whitespace-nowrap ml-2">{{ $conv['last_message']->created_at->diffForHumans() }}</span>
                        </div>
                        @if($conv['item'])
                            <p class="text-xs text-bekas-green font-bold uppercase tracking-wider truncate mb-1">
                                {{ $conv['item']->name }}
                            </p>
                        @endif
                        <p class="text-sm text-gray-600 truncate {{ $conv['unread'] > 0 ? 'font-semibold text-gray-900' : '' }}">
                            {{ $conv['last_message']->sender_id == Auth::id() ? 'Anda: ' : '' }}{{ $conv['last_message']->message }}
                        </p>
                    </div>
                    @if($conv['unread'] > 0)
                        <div class="w-5 h-5 bg-bekas-green rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0">
                            {{ $conv['unread'] }}
                        </div>
                    @endif
                </a>
            @empty
                <div class="p-10 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <p>Belum ada pesan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
