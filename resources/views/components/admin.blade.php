<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Campsite Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex h-screen font-inter">

    <!-- Sidebar -->
    <aside class="w-72 bg-gradient-to-b from-green-700 via-green-800 to-gray-900 text-white flex flex-col shadow-2xl">
        
        <!-- Brand -->
        <div class="flex items-center gap-3 p-6 border-b border-green-600/40">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <span class="text-2xl font-extrabold tracking-tight text-white drop-shadow-lg">CampManager</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 mt-6 px-4 space-y-2">
            <a href="/" 
               class="group flex items-center gap-4 p-3 rounded-xl transition-all hover:bg-green-600/30 hover:translate-x-1">
                <svg class="w-6 h-6 group-hover:text-green-200 text-green-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-semibold text-lg group-hover:text-green-200">Dashboard</span>
            </a>

            <a href="/tents" 
               class="group flex items-center gap-4 p-3 rounded-xl transition-all hover:bg-green-600/30 hover:translate-x-1">
                <svg class="w-6 h-6 group-hover:text-green-200 text-green-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-8 8h8m-8 4h8m-8 4h8"></path>
                </svg>
                <span class="font-semibold text-lg group-hover:text-green-200">Manage Tents</span>
            </a>

            <a href="/user" 
               class="group flex items-center gap-4 p-3 rounded-xl transition-all hover:bg-green-600/30 hover:translate-x-1">
                <svg class="w-6 h-6 group-hover:text-green-200 text-green-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7a3 3 0 11-6 0 3 3 0 016 0zm6 0a3 3 0 11-6 0 3 3 0 016 0zM21 10a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0zM7 20H3v-2a3 3 0 015.356-1.857M7 20v-2a3 3 0 015.356-1.857M17 20h4v-2a3 3 0 00-5.356-1.857M17 20v-2a3 3 0 00-5.356-1.857"></path>
                </svg>
                <span class="font-semibold text-lg group-hover:text-green-200">Bookings</span>
            </a>

            <a href="/settings" 
               class="group flex items-center gap-4 p-3 rounded-xl transition-all hover:bg-green-600/30 hover:translate-x-1">
                <svg class="w-6 h-6 group-hover:text-green-200 text-green-100 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-lg group-hover:text-green-200">Settings</span>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-6 mt-auto border-t border-green-600/40">
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-3 rounded-xl shadow-lg transition-all">
                    Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-8">
        {{ $slot }}
    </main>

</body>
</html>
