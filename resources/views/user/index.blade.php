<x-layout>
    <style>
        /* Hide number input spinners */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Skeleton Loading Animation */
        @keyframes skeleton-loading {
            0% {
                background-position: -200px 0;
            }
            100% {
                background-position: calc(200px + 100%) 0;
            }
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200px 100%;
            animation: skeleton-loading 1.5s ease-in-out infinite;
        }
    </style>
    <div class="space-y-12 pb-24">
        <!-- Hero Section -->
        <div class="relative min-h-[300px] h-auto pb-16 md:pb-0 md:h-[300px] overflow-hidden rounded-2xl group shadow-md">
            <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1770&q=80" 
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" alt="Camping Background">
            <div class="absolute inset-0 bg-gradient-to-r from-stone-900/80 via-stone-900/40 to-transparent flex items-center px-12 py-12 md:py-0">
                <div class="max-w-xl">
                    <h1 class="text-3xl md:text-5xl font-light text-white mb-4 tracking-tight leading-tight">
                        Escape to <span class="text-stone-300 font-medium">Tam Durian Farm & Campsite</span>
                    </h1>
                    <p class="text-stone-200 text-base md:text-lg font-light tracking-wide leading-relaxed">
                        Experience the perfect blend of agricultural charm and nature adventure in the heart of Jasin, Melaka.
                    </p>
                </div>
            </div>

            <div class="hidden md:block absolute bottom-10 left-12 right-12 z-20">
                <form id="search-form-desktop" action="{{ route('booking.index') }}" method="GET" class="bg-white/95 backdrop-blur-md p-2 rounded-xl shadow-lg flex items-center gap-4 border border-stone-200/50">
                    <div class="flex-1 px-6 py-3 border-r border-stone-100 flex flex-col justify-center">
                        <label for="check_in" class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-1">Check-in</label>
                        <input type="date" id="check_in" name="check_in" value="{{ old('check_in', $currentDate) }}" 
                               min="{{ date('Y-m-d') }}"
                               class="text-sm font-medium text-stone-900 bg-transparent border-none p-0 focus:ring-0 cursor-pointer w-full">
                    </div>
                    <div class="flex-1 px-6 py-3 border-r border-stone-100 flex flex-col justify-center">
                        <label for="check_out" class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-1">Check-out</label>
                        <input type="date" id="check_out" name="check_out" value="{{ old('check_out', $nextDate) }}" 
                               class="text-sm font-medium text-stone-900 bg-transparent border-none p-0 focus:ring-0 cursor-pointer w-full">
                    </div>
                    <div class="flex-1 px-6 py-3 border-r border-stone-100 flex flex-col justify-center">
                        <label for="adults" class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-1">Adults</label>
                        <div class="flex items-center gap-2">
                             <input type="number" id="adults" name="adults" min="1" value="{{ old('adults', $adults) }}" 
                               class="text-sm font-medium text-stone-900 bg-transparent border-none p-0 focus:ring-0 w-8 text-center md:text-left">
                        </div>
                    </div>
                    <div class="flex-1 px-6 py-3 flex flex-col justify-center">
                        <label for="children" class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-1">Children</label>
                        <div class="flex items-center gap-2">
                             <input type="number" id="children" name="children" min="0" value="{{ old('children', $children) }}" 
                               class="text-sm font-medium text-stone-900 bg-transparent border-none p-0 focus:ring-0 w-8 text-center md:text-left">
                        </div>
                    </div>
                    <button type="submit" id="search-btn-desktop" class="bg-stone-900 hover:bg-stone-800 text-white px-8 py-4 rounded-xl font-medium tracking-wide text-sm transition-all transform hover:-translate-y-0.5 shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="search-btn-text">Search Availability</span>
                        <span class="search-btn-loading hidden">Searching...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Responsive Search Bar (Mobile Position) -->
        <div class="md:hidden relative px-4 z-20">
            <form id="search-form-mobile" action="{{ route('booking.index') }}" method="GET" class="bg-white/95 backdrop-blur-md p-6 rounded-2xl shadow-lg flex flex-col gap-6 border border-stone-100">
                <div class="flex flex-col gap-4">
                    <div class="flex-1 border-b border-stone-100 pb-4">
                        <label for="check_in_mobile" class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-1 block">Check-in</label>
                        <input type="date" id="check_in_mobile" name="check_in"  value="{{ old('check_in', $currentDate) }}"  
                               min="{{ date('Y-m-d') }}"
                               class="text-sm font-medium text-stone-900 bg-transparent border-none p-0 focus:ring-0 cursor-pointer w-full">
                    </div>
                    <div class="flex-1 border-b border-stone-100 pb-4">
                        <label for="check_out_mobile" class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-1 block">Check-out</label>
                        <input type="date" id="check_out_mobile" name="check_out" value="{{ old('check_out', $nextDate) }}" 
                               class="text-sm font-medium text-stone-900 bg-transparent border-none p-0 focus:ring-0 cursor-pointer w-full">
                    </div>
                    <div class="flex-1 border-b border-stone-100 pb-4">
                        <label for="adults_mobile" class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-1 block">Adults</label>
                        <div class="flex items-center gap-4">
                             <input type="number" id="adults_mobile" name="adults" min="1" value="{{ old('adults', $adults) }}" 
                                    class="text-sm font-medium text-stone-900 bg-transparent border-none p-0 focus:ring-0 w-8 text-left">
                        </div>
                    </div>
                    <div class="flex-1">
                        <label for="children_mobile" class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-1 block">Children</label>
                        <div class="flex items-center gap-4">
                             <input type="number" id="children_mobile" name="children" min="0" value="{{ old('children', $children) }}" 
                                    class="text-sm font-medium text-stone-900 bg-transparent border-none p-0 focus:ring-0 w-8 text-left">
                        </div>
                    </div>
                </div>
                <button type="submit" id="search-btn-mobile" class="bg-stone-900 hover:bg-stone-800 text-white py-4 rounded-xl font-medium tracking-wide text-sm transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="search-btn-text">Search Availability</span>
                    <span class="search-btn-loading hidden">Searching...</span>
                </button>
            </form>
        </div>

        @if(isset($upcomingBlockouts) && $upcomingBlockouts->count() > 0)
            <div class="mt-6 p-4 bg-stone-50 border border-stone-200 rounded-xl flex items-start gap-4">
                <div class="text-stone-400 mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-stone-800 uppercase tracking-widest">Campsite Closures Note</h3>
                    <ul class="mt-2 space-y-1 text-sm font-medium text-stone-600">
                        @foreach($upcomingBlockouts as $blockout)
                            <li>&bull; {{ $blockout->start_date->format('M d, Y') }} - {{ $blockout->end_date->format('M d, Y') }} 
                                <span class="text-stone-400 font-light italic">({{ $blockout->reason ?: 'Private Event' }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Section Title & Filter (Visual) -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-stone-200 pb-6">
            <div>
                <h2 class="text-3xl font-light tracking-tight text-stone-900">Available Packages</h2>
                <p class="text-stone-500 font-medium tracking-wide">Compare our premium campsites at a glance</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest">Sort By:</span>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'recommended']) }}" 
                   class="px-5 py-2 {{ $sort === 'recommended' ? 'bg-stone-900 text-white border-stone-900' : 'bg-transparent text-stone-600 border-stone-200 hover:border-stone-400 hover:text-stone-900' }} border rounded-full text-xs font-medium tracking-wider transition-colors">
                   Recommended
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" 
                   class="px-5 py-2 {{ $sort === 'price_low' ? 'bg-stone-900 text-white border-stone-900' : 'bg-transparent text-stone-600 border-stone-200 hover:border-stone-400 hover:text-stone-900' }} border rounded-full text-xs font-medium tracking-wider transition-colors">
                   Lowest Price
                </a>
            </div>
        </div>

        <!-- Tent List Container -->
        <div id="tent-list-wrapper">
            @include('user.partials.tent-list', ['tents' => $tents])
        </div>

        
        <!-- Pagination UI (Dynamic) -->
        <div class="mt-12">
            {{ $tents->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            const checkInMobile = document.getElementById('check_in_mobile');
            const checkOutMobile = document.getElementById('check_out_mobile');

            function handleCheckInChange(inInput, outInput) {
                if (!inInput.value) return;
                
                let date = new Date(inInput.value);
                date.setDate(date.getDate() + 1);
                
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const nextDay = `${year}-${month}-${day}`;
                
                outInput.value = nextDay;
                outInput.min = nextDay;
            }

            if (checkInInput && checkOutInput) {
                checkInInput.addEventListener('change', () => handleCheckInChange(checkInInput, checkOutInput));
            }
            
            if (checkInMobile && checkOutMobile) {
                checkInMobile.addEventListener('change', () => handleCheckInChange(checkInMobile, checkOutMobile));
            }
        });
    </script>
</x-layout>
