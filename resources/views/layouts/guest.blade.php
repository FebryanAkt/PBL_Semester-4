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

    @include('partials.header_guest') <!-- Menggunakan Header Khusus Guest -->

    <main>
        @yield('content')
    </main>

</body>

</html>