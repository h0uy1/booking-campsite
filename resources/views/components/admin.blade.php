<!DOCTYPE html>
<html lang="en" class="bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Campsite Booking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Global Theme Transitions */
        * { transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease; }
        
        /* Dark Mode Overrides for Inner Content */
        .dark .bg-white { background-color: #030712 !important; } /* gray-950 */
        .dark .bg-gray-50 { background-color: #030712 !important; }
        .dark .bg-gray-50\/50 { background-color: rgba(17, 24, 39, 0.5) !important; }
        
        .dark .text-gray-900 { color: #f9fafb !important; } /* gray-50 */
        .dark .text-gray-800 { color: #f3f4f6 !important; } /* gray-100 */
        .dark .text-gray-700 { color: #e5e7eb !important; } /* gray-200 */
        .dark .text-gray-600 { color: #d1d5db !important; } /* gray-300 */
        .dark .text-gray-500 { color: #9ca3af !important; } /* gray-400 */
        .dark .text-gray-400 { color: #9ca3af !important; } /* Brighter icons/secondary text */
        
        .dark .border-gray-100,
        .dark .border-gray-200,
        .dark .border-gray-300 { border-color: #1f2937 !important; } /* gray-800 */
        
        .dark .divide-gray-100 > * + *,
        .dark .divide-gray-200 > * + * { border-color: #1f2937 !important; }
        
        .dark .hover\:bg-gray-50:hover { background-color: #111827 !important; } /* gray-900 */

        /* Badge backgrounds in dark mode */
        .dark .bg-green-50 { background-color: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }
        .dark .bg-blue-50 { background-color: rgba(59, 130, 246, 0.1) !important; color: #3b82f6 !important; }
        .dark .bg-yellow-50 { background-color: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }
        .dark .bg-red-50 { background-color: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; }
        .dark .bg-purple-50 { background-color: rgba(139, 92, 246, 0.1) !important; color: #8b5cf6 !important; }
        .dark .bg-orange-50 { background-color: rgba(249, 115, 22, 0.1) !important; color: #f97316 !important; }
        
        /* Table Specifics */
        .dark thead.bg-gray-50 { background-color: #111827 !important; }
        .dark td.text-gray-900 { color: #f9fafb !important; }
        
        /* Shadows adjustment */
        .dark .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5) !important; }
        
        /* Scrollbar for Dark Mode */
        .dark ::-webkit-scrollbar { width: 8px; }
        .dark ::-webkit-scrollbar-track { background: #030712; }
        .dark ::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #374151; }
    </style>
</head>
<body class="font-sans antialiased overflow-hidden flex h-screen" 
      :class="darkMode ? 'dark bg-gray-950 text-gray-100' : 'bg-gray-50 text-gray-900'"
      x-data="{ 
          sidebarOpen: false, 
          desktopSidebarOpen: true, 
          darkMode: localStorage.getItem('admin-theme') === 'dark',
          toggleTheme() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('admin-theme', this.darkMode ? 'dark' : 'light');
          }
      }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black/50 lg:hidden backdrop-blur-sm" @click="sidebarOpen = false" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-30 w-72 bg-gray-900 text-white flex flex-col shadow-2xl transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-auto"
           x-show="desktopSidebarOpen"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-300"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           style="display: flex;">
        
        <!-- Brand -->
        <div class="flex items-center justify-between p-6 border-b border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">CampManager</span>
            </div>
            <!-- Mobile close button -->
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 mt-6 px-4 space-y-1 overflow-y-auto">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Main Menu</p>
            
            <a href="/admin/dashboard" 
               class="{{ request()->is('admin/dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} group flex items-center gap-3 p-3 rounded-xl transition-all">
                <svg class="w-5 h-5 {{ request()->is('admin/dashboard') ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="/admin/tents" 
               class="{{ request()->is('admin/tents*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} group flex items-center gap-3 p-3 rounded-xl transition-all">
                <svg class="w-5 h-5 {{ request()->is('admin/tents*') ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="font-medium">Inventory</span>
            </a>

            <a href="/admin/bookings" 
               class="{{ request()->is('admin/bookings') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} group flex items-center gap-3 p-3 rounded-xl transition-all">
                <svg class="w-5 h-5 {{ request()->is('admin/bookings') ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-8 8h8m-8 4h8m-8 4h8"></path>
                </svg>
                <span class="font-medium">Bookings</span>
                @php
                    $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="/admin/bookings/occupancy" 
               class="{{ request()->is('admin/bookings/occupancy') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} group flex items-center gap-3 p-3 rounded-xl transition-all">
                <svg class="w-5 h-5 {{ request()->is('admin/bookings/occupancy') ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                </svg>
                <span class="font-medium">Occupancy Matrix</span>
            </a>

            <a href="/admin/blockouts" 
               class="{{ request()->is('admin/blockouts*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} group flex items-center gap-3 p-3 rounded-xl transition-all">
                <svg class="w-5 h-5 {{ request()->is('admin/blockouts*') ? 'text-red-400' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="font-medium">Blockout Dates</span>
            </a>

            <a href="/admin/slots" 
               class="{{ request()->is('admin/slots*') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} group flex items-center gap-3 p-3 rounded-xl transition-all">
                <svg class="w-5 h-5 {{ request()->is('admin/slots*') ? 'text-green-400' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="font-medium">Slots Status</span>
            </a>
        </nav>

        <!-- User Profile / Logout -->
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-800 mb-3">
                <div class="h-9 w-9 rounded-lg bg-green-500 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth('admin')->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 text-sm text-gray-400 hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden transition-colors duration-300"
         :class="darkMode ? 'bg-gray-950' : 'bg-gray-50'">
        
        <!-- Top Navigation -->
        <header class="shadow-sm border-b z-10 transition-colors duration-300"
                :class="darkMode ? 'bg-gray-900 border-gray-800' : 'bg-white border-gray-200'">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                <!-- Left side -->
                <div class="flex items-center space-x-4">
                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-900 focus:outline-none lg:hidden">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <!-- Desktop Toggle -->
                    <button @click="desktopSidebarOpen = !desktopSidebarOpen" 
                            class="hidden lg:block text-gray-500 hover:text-gray-900 focus:outline-none p-1.5 rounded-lg border transition-all"
                            :class="darkMode ? 'bg-gray-800 border-gray-700 hover:bg-gray-700' : 'bg-gray-50 border-gray-200 hover:bg-gray-100'">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                    </button>

                    <!-- Search -->
                    <div class="hidden md:flex relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" 
                               class="block w-64 rounded-full border-0 py-1.5 pl-9 pr-3 transition-all ring-1 ring-inset sm:text-sm sm:leading-6"
                               :class="darkMode ? 'bg-gray-800 border-gray-700 text-white ring-gray-700 focus:ring-green-500 placeholder:text-gray-500' : 'bg-gray-50 border-gray-300 text-gray-900 ring-gray-300 focus:ring-green-600 placeholder:text-gray-400'"
                               placeholder="Search bookings or guests...">
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    @php
                        $unseenBookings = \App\Models\Booking::where('is_seen', false)
                            ->with(['slot.tent'])
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
                        $unseenCount = \App\Models\Booking::where('is_seen', false)->count();
                    @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" 
                                class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none rounded-lg border transition-all"
                                :class="darkMode ? 'bg-gray-800 border-gray-700 hover:bg-gray-700' : 'bg-gray-50 border-transparent hover:border-gray-200'">
                            @if($unseenCount > 0)
                                <span class="absolute top-1 right-1 h-4 w-4 rounded-full bg-red-500 ring-2 ring-white text-[10px] text-white font-black flex items-center justify-center">
                                    {{ $unseenCount > 9 ? '9+' : $unseenCount }}
                                </span>
                            @endif
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 mt-3 w-80 rounded-2xl shadow-2xl border py-2 origin-top-right z-50"
                             :class="darkMode ? 'bg-gray-900 border-gray-800' : 'bg-white border-gray-100'"
                             style="display: none;">
                            <div class="px-4 py-3 border-b flex items-center justify-between"
                                 :class="darkMode ? 'border-gray-800' : 'border-gray-50'">
                                <h3 class="text-xs font-black uppercase tracking-widest" :class="darkMode ? 'text-gray-400' : 'text-gray-900'">New Bookings</h3>
                                <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">{{ $unseenCount }} New</span>
                            </div>
                            
                            <div class="max-h-96 overflow-y-auto">
                                @forelse($unseenBookings as $notif)
                                    <a href="/admin/bookings?search={{ $notif->id }}" 
                                       class="flex items-start gap-3 px-4 py-3 transition-colors border-b last:border-0"
                                       :class="darkMode ? 'hover:bg-gray-800 border-gray-800' : 'hover:bg-gray-50 border-gray-50'">
                                        <div class="h-10 w-10 flex-shrink-0 rounded-xl flex items-center justify-center"
                                             :class="darkMode ? 'bg-gray-800' : 'bg-gray-100'">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-8 8h8m-8 4h8m-8 4h8"></path></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold truncate" :class="darkMode ? 'text-gray-100' : 'text-gray-900'">New Booking #{{ $notif->id }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $notif->slot->tent->name ?? 'Package' }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1 font-medium">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3"
                                             :class="darkMode ? 'bg-gray-800' : 'bg-gray-50'">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        </div>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">No new bookings</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="p-2 border-t" :class="darkMode ? 'border-gray-800' : 'border-gray-50'">
                                <a href="/admin/bookings" class="flex items-center justify-center w-full py-2 text-[10px] font-black text-gray-500 uppercase tracking-widest hover:text-green-600 transition-colors">
                                    View All Bookings
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Theme Toggle (Settings Icon) -->
                    <button @click="toggleTheme()" 
                            class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none rounded-lg border transition-all"
                            :class="darkMode ? 'bg-gray-800 border-gray-700 hover:bg-gray-700 text-yellow-500' : 'bg-gray-50 border-transparent hover:border-gray-200 text-gray-400'">
                        <!-- Sun Icon (for dark mode) -->
                        <svg x-show="darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon (for light mode) -->
                        <svg x-show="!darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
