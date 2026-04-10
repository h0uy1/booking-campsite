<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tam Durian Farm & Campsite</title>
    <link rel="icon" type="image/png" href="{{ asset('site2.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-stone-50 text-stone-900 antialiased selection:bg-stone-200 selection:text-stone-900">

<!-- Navbar -->
<nav class="bg-white/95 backdrop-blur-sm border-b border-stone-200 sticky top-0 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Left: Brand + Links -->
            <div class="flex items-center">
                <span class="text-xl font-light tracking-widest text-stone-900 flex items-center gap-2 uppercase">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    CampExplore
                </span>
                <div class="hidden md:flex ml-10 space-x-8">
                    <a href="/user" class="border-transparent text-stone-500 hover:text-stone-900 hover:border-stone-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium tracking-wide transition-colors">Find a Campsite</a>
                    @auth
                        <a href="/all" class="border-transparent text-stone-500 hover:text-stone-900 hover:border-stone-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium tracking-wide transition-colors">My Bookings</a>
                    @endauth
                </div>
            </div>

            <!-- Right: User / Auth -->
            <div class="flex items-center space-x-6">
                @guest
                    <a href="{{ url('/login') }}" class="text-stone-500 hover:text-stone-900 text-sm font-medium tracking-wide transition-colors">Login</a>
                    <a href="{{ url('/register') }}" class="text-stone-900 border border-stone-200 hover:bg-stone-50 px-4 py-2 rounded-lg text-sm font-medium tracking-wide transition-colors">Register</a>
                @endguest

                @auth
                    <span class="hidden md:flex items-center text-sm font-light tracking-wide text-stone-600">Hi, {{ auth()->user()->name }}</span>
                    
                    <form method="POST" action="{{ url('/logout') }}">
                        @csrf
                        <button type="submit" 
                                class="ml-2 text-white bg-stone-900 hover:bg-stone-800 px-4 py-2 rounded-lg text-sm font-medium tracking-wide transition-colors shadow-sm">
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
