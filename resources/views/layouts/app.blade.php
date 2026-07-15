<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Help Center') - Cendekia</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVJkEZSMUkrQ6usKGiOW03ijLNnsuXvZNi3MWuSXVZB8QVL0QkC02eNQU3IQu854PNu7pljiMFA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Cendekia" class="w-8 h-8">
                <span class="font-bold text-lg text-gray-800">Cendekia</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('help-center.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Bantuan</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700 font-medium">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-100 py-8 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="font-bold mb-4">Tentang Cendekia</h3>
                    <p class="text-gray-400 text-sm">Platform pembelajaran online terpadu untuk institusi pendidikan.</p>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Tautan Cepat</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('help-center.index') }}" class="text-gray-400 hover:text-white">Pusat Bantuan</a></li>
                        <li><a href="{{ route('help-center.guides') }}" class="text-gray-400 hover:text-white">Panduan</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white">Dashboard</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Kontak Support</h3>
                    <p class="text-gray-400 text-sm">Email: support@cendekia.local</p>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; 2024-2026 Cendekia. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
