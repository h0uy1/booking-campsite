<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campsite Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

<!-- Navbar -->
<nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left: Brand + Links -->
            <div class="flex items-center">
                <span class="text-xl font-bold text-green-700 flex items-center gap-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    CampManager
                </span>
                <div class="hidden md:flex ml-10 space-x-8">
                    <a href="/" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Dashboard</a>
                    <a href="/user" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Bookings</a>
                    <a href="/tents" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Campsites</a>
                </div>
            </div>

            <!-- Right: User / Auth -->
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ url('/login') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Login</a>
                    <a href="{{ url('/register') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">Register</a>
                @endguest

                @auth
                    <span class="hidden md:flex items-center text-sm text-gray-700">Hi, {{ auth()->user()->name }}</span>
                    
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" 
                                class="ml-2 text-white bg-green-600 hover:bg-green-700 px-3 py-1.5 rounded text-sm font-medium transition">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>

        </div>
    </div>
</nav>

<!-- Content -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{ $slot }}
</main>

<script>
    // Global fix to disable scroll-to-increment on number inputs (Mac trackpad/Mouse wheel)
    document.addEventListener('wheel', function(e) {
        if (e.target.type === 'number' && document.activeElement === e.target) {
            e.preventDefault();
        }
    }, { passive: false });
</script>
</body>
</html>
