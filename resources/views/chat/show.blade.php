@extends('layouts.app')

@section('title', 'Chat dengan ' . $partner->name . ' - Bekaswit')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 h-[calc(100vh-80px)] flex flex-col">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-full overflow-hidden">
        
        <!-- Chat Header -->
        <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 shrink-0">
            <div class="flex items-center gap-3">
                <a href="{{ route('chat.index') }}" class="p-2 text-gray-500 hover:text-bekas-green hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div class="w-10 h-10 rounded-full bg-bekas-green/10 flex items-center justify-center text-bekas-green font-bold shrink-0">
                    {{ substr($partner->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">{{ $partner->name }}</h2>
                    <p class="text-xs text-gray-500">{{ $partner->isSeller() ? 'Penjual Bekaswit' : 'Pembeli Bekaswit' }}</p>
                </div>
            </div>
        </div>

        <!-- Optional Item Context -->
        @if(isset($itemContext) && $itemContext)
            <div class="bg-blue-50/50 border-b border-blue-100 p-3 flex items-center gap-3 shrink-0">
                <div class="w-12 h-12 rounded-lg bg-white overflow-hidden border border-blue-100 shrink-0">
                    <img src="{{ asset('images/' . $itemContext->image) }}" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-0.5">Menanyakan Barang</p>
                    <a href="{{ route('produk.detail', $itemContext->id) }}" class="text-sm font-semibold text-gray-800 hover:text-bekas-green truncate block">{{ $itemContext->name }}</a>
                </div>
            </div>
        @endif

        <!-- Chat Area -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-white space-y-4">
            @if($messages->isEmpty())
                <div class="h-full flex flex-col items-center justify-center text-gray-400">
                    <p class="mb-2">Mulai percakapan dengan <strong>{{ $partner->name }}</strong></p>
                    <span class="text-xs">Sampaikan apa yang ingin Anda tanyakan.</span>
                </div>
            @endif

            @foreach($messages as $msg)
                @if($msg->sender_id == Auth::id())
                    <!-- My Message -->
                    <div class="flex justify-end message-item" data-id="{{ $msg->id }}">
                        <div class="bg-bekas-green text-white max-w-[75%] rounded-2xl rounded-tr-sm px-4 py-2 shadow-sm">
                            <p class="text-sm">{{ $msg->message }}</p>
                            <span class="text-[10px] text-green-100 mt-1 block text-right">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @else
                    <!-- Partner Message -->
                    <div class="flex justify-start message-item" data-id="{{ $msg->id }}">
                        <div class="bg-gray-100 text-gray-800 max-w-[75%] rounded-2xl rounded-tl-sm px-4 py-2 border border-gray-200">
                            <p class="text-sm">{{ $msg->message }}</p>
                            <span class="text-[10px] text-gray-400 mt-1 block">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Chat Input -->
        <div class="p-4 border-t border-gray-100 bg-white shrink-0">
            <form id="chat-form" class="flex gap-2">
                @csrf
                <input type="hidden" name="item_id" value="{{ request('item_id') }}">
                <input type="text" id="message-input" name="message" class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-bekas-green/20 focus:border-bekas-green" placeholder="Ketik pesan..." required autocomplete="off">
                <button type="submit" id="send-btn" class="bg-bekas-dark hover:bg-gray-800 text-white w-12 h-12 rounded-xl flex items-center justify-center transition-colors shrink-0">
                    <svg class="w-5 h-5 translate-x-[-1px] translate-y-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- AJAX Script for "Real-Time" simulation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    
    // Scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    scrollToBottom();

    // Last message ID known
    let lastMessageId = 0;
    const items = document.querySelectorAll('.message-item');
    if(items.length > 0) {
        lastMessageId = items[items.length - 1].dataset.id;
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }

    // Send Message
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const text = messageInput.value.trim();
        if(!text) return;
        
        // Remove empty state if exists
        const emptyState = chatMessages.querySelector('.h-full.flex-col');
        if(emptyState) emptyState.remove();

        const originalBtnHtml = sendBtn.innerHTML;
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<svg class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        
        fetch("{{ route('chat.store', $partner->id) }}", {
            method: 'POST',
            body: new FormData(chatForm),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                messageInput.value = '';
                appendMessage(data.message);
            }
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = originalBtnHtml;
        });
    });

    function appendMessage(msg) {
        // Prevent duplicate appends
        if(document.querySelector('.message-item[data-id="' + msg.id + '"]')) return;
        
        const div = document.createElement('div');
        div.className = msg.is_mine ? "flex justify-end message-item" : "flex justify-start message-item";
        div.dataset.id = msg.id;
        
        if(msg.is_mine) {
            div.innerHTML = `
                <div class="bg-bekas-green text-white max-w-[75%] rounded-2xl rounded-tr-sm px-4 py-2 shadow-sm transform transition-all duration-300 scale-100 opacity-100">
                    <p class="text-sm">${escapeHtml(msg.text)}</p>
                    <span class="text-[10px] text-green-100 mt-1 block text-right">${msg.time}</span>
                </div>
            `;
        } else {
            div.innerHTML = `
                <div class="bg-gray-100 text-gray-800 max-w-[75%] rounded-2xl rounded-tl-sm px-4 py-2 border border-gray-200 transform transition-all duration-300 scale-100 opacity-100">
                    <p class="text-sm">${escapeHtml(msg.text)}</p>
                    <span class="text-[10px] text-gray-400 mt-1 block">${msg.time}</span>
                </div>
            `;
        }
        
        chatMessages.appendChild(div);
        scrollToBottom();
        lastMessageId = Math.max(lastMessageId, msg.id);
    }

    // Polling for "Real-Time" feel every 3 seconds
    setInterval(() => {
        fetch(@json(route('chat.show', ['id' => $partner->id, 'item_id' => request('item_id')])), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.messages) {
                let hasNew = false;
                data.messages.forEach(msg => {
                    if (msg.id > lastMessageId) {
                        appendMessage(msg);
                        hasNew = true;
                    }
                });
            }
        });
    }, 3000);
});
</script>
@endsection
