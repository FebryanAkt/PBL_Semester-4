<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bekaswit - Beli Seken, Hemat & Sustainable')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bekas-dark': '#133045',
                        'bekas-green': '#3a7d44',
                        'bekas-bg': '#e8f1f5',
                    }
                }
            }
        }
    </script>
</head>

<body class="flex min-h-screen flex-col bg-bekas-bg font-sans antialiased text-gray-800">

    @include('partials.header')

    <!-- ==================== NOTIFIKASI BELL (UNTUK PENJUAL) ==================== -->
    @auth
    @if(auth()->user()->isSeller())
    <div class="fixed top-20 right-4 z-50" id="notification-area">
        <div class="relative" id="notification-dropdown">
            <button id="notificationBell" class="relative p-2 bg-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none">
                <svg class="w-6 h-6 text-bekas-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span id="notifBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center hidden">
                    0
                </span>
            </button>

            <div id="notifDropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 hidden transition-all duration-200 transform origin-top-right scale-95 opacity-0">
                <div class="p-3 border-b border-gray-100 font-bold text-gray-700 flex items-center justify-between">
                    <span>Notifikasi</span>
                    <span class="text-xs text-gray-400" id="notifCount"></span>
                </div>
                <div id="notifList" class="max-h-96 overflow-y-auto divide-y divide-gray-100">
                    <div class="p-4 text-center text-gray-500 text-sm">Memuat...</div>
                </div>
                <div class="p-2 border-t border-gray-100 text-center">
                    <a href="{{ route('penjual.orders.index') }}" class="text-xs text-bekas-green hover:underline">Lihat semua pesanan</a>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth

    <!-- Container Notifikasi Flash (sukses/error) -->
    @if(session('success'))
    <div id="flash-message" class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 transition-all duration-500 ease-out flex items-center shadow-md bg-green-50 text-bekas-green border border-green-200 px-6 py-4 rounded-xl gap-3 min-w-[300px]">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-medium flex-1">{{ session('success') }}</span>
        <button onclick="document.getElementById('flash-message').style.display='none'" class="text-green-500 hover:text-green-700 focus:outline-none shrink-0 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.style.opacity = '0';
                flash.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => flash.remove(), 500);
            }
        }, 4000);
    </script>
    @endif

    @if(session('error') || $errors->any())
    <div id="error-message" class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 transition-all duration-500 ease-out flex items-center shadow-md bg-red-50 text-red-700 border border-red-200 px-6 py-4 rounded-xl gap-3 min-w-[300px]">
        <span class="font-medium flex-1">{{ session('error') ?? $errors->first() }}</span>
        <button onclick="document.getElementById('error-message').style.display='none'" class="text-red-500 hover:text-red-700 focus:outline-none shrink-0 cursor-pointer">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Script Notifikasi (hanya untuk penjual) -->
    @auth
    @if(auth()->user()->isSeller())
    <script>
        // Fungsi fetch notifikasi
        async function fetchNotifications() {
            try {
                const response = await fetch('{{ route("notifications.fetch") }}');
                if (!response.ok) throw new Error('Gagal fetch');
                const data = await response.json();
                const unreadCount = data.filter(n => !n.read_at).length;
                const badge = document.getElementById('notifBadge');
                if (badge) {
                    if (unreadCount > 0) {
                        badge.textContent = unreadCount;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
                const countSpan = document.getElementById('notifCount');
                if (countSpan) countSpan.innerText = `${data.length} notifikasi`;

                renderDropdown(data);
            } catch (err) {
                console.error('Polling error:', err);
            }
        }

        function renderDropdown(notifications) {
            const container = document.getElementById('notifList');
            if (!container) return;
            if (notifications.length === 0) {
                container.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>';
                return;
            }
            let html = '';
            notifications.forEach(notif => {
                const data = notif.data;
                const isRead = notif.read_at !== null;
                const bgClass = isRead ? 'bg-white' : 'bg-bekas-green/5';
                html += `
                    <div class="p-3 hover:bg-gray-50 transition cursor-pointer ${bgClass}" data-id="${notif.id}" onclick="markAsRead('${notif.id}', '${data.url}')">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-bekas-green/20 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">${data.item_name}</p>
                                <p class="text-xs text-gray-500 mt-1">${data.message.substring(0, 80)}${data.message.length > 80 ? '...' : ''}</p>
                                <span class="text-[10px] text-gray-400 mt-1 block">${new Date(notif.created_at).toLocaleString()}</span>
                            </div>
                            ${!isRead ? '<div class="w-2 h-2 bg-bekas-green rounded-full mt-2"></div>' : ''}
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        }

        async function markAsRead(notificationId, url) {
            try {
                await fetch('{{ route("notifications.mark-read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: notificationId })
                });
                window.location.href = url;
            } catch (error) {
                window.location.href = url;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const bell = document.getElementById('notificationBell');
            const dropdown = document.getElementById('notifDropdown');
            if (bell && dropdown) {
                bell.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (dropdown.classList.contains('hidden')) {
                        dropdown.classList.remove('hidden');
                        setTimeout(() => {
                            dropdown.classList.remove('scale-95', 'opacity-0');
                            dropdown.classList.add('scale-100', 'opacity-100');
                        }, 10);
                        fetchNotifications();
                    } else {
                        dropdown.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => dropdown.classList.add('hidden'), 200);
                    }
                });
                document.addEventListener('click', function() {
                    if (!dropdown.classList.contains('hidden')) {
                        dropdown.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => dropdown.classList.add('hidden'), 200);
                    }
                });
            }
            // polling setiap 30 detik
            setInterval(fetchNotifications, 30000);
            fetchNotifications();
        });
    </script>
    @endif
    @endauth
</body>

</html>
