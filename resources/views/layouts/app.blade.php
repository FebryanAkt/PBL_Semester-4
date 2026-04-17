<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bekaswit - Beli Seken, Hemat & Sustainable')</title>
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

<body class="bg-bekas-bg font-sans antialiased text-gray-800">

    @include('partials.header')

    <!-- Container Notifikasi -->
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
        // Otomatis hilangkan notifikasi setelah 4 detik
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

    <main>
        @yield('content')
    </main>

</body>

</html>