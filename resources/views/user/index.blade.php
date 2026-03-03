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
        <div class="relative min-h-[300px] h-auto pb-16 md:pb-0 md:h-[300px] overflow-hidden rounded-3xl group shadow-2xl">
            <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1770&q=80" 
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Camping Background">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900/70 via-gray-900/30 to-transparent flex items-center px-12 py-12 md:py-0">
                <div class="max-w-xl">
                    <h1 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight">
                        Escape to <span class="text-green-400">Tam Durian Farm & Campsite</span>
                    </h1>
                    <p class="text-gray-100 text-base md:text-lg font-medium leading-relaxed">
                        Experience the perfect blend of agricultural charm and nature adventure in the heart of Jasin, Melaka.
                    </p>
                </div>
            </div>

            <div class="hidden md:block absolute bottom-10 left-12 right-12 z-20">
                <form id="search-form-desktop" action="{{ route('booking.index') }}" method="GET" class="bg-white/95 backdrop-blur-md p-2 rounded-2xl shadow-2xl flex items-center gap-4 border border-white/20">
                    <div class="flex-1 px-6 py-3 border-r border-gray-100 flex flex-col justify-center">
                        <label for="check_in" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Check-in</label>
                        <input type="date" id="check_in" name="check_in" value="{{ old('check_in', $currentDate) }}" 
                               min="{{ date('Y-m-d') }}"
                               class="text-sm font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0 cursor-pointer w-full">
                    </div>
                    <div class="flex-1 px-6 py-3 border-r border-gray-100 flex flex-col justify-center">
                        <label for="check_out" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Check-out</label>
                        <input type="date" id="check_out" name="check_out" value="{{ old('check_out', $nextDate) }}" 
                               class="text-sm font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0 cursor-pointer w-full">
                    </div>
                    <div class="flex-1 px-6 py-3 border-r border-gray-100 flex flex-col justify-center">
                        <label for="adults" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Adults</label>
                        <div class="flex items-center gap-2">
                             <input type="number" id="adults" name="adults" min="1" value="{{ old('adults', $adults) }}" 
                               class="text-sm font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0 w-8 text-center md:text-left">
                        </div>
                    </div>
                    <div class="flex-1 px-6 py-3 flex flex-col justify-center">
                        <label for="children" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Children</label>
                        <div class="flex items-center gap-2">
                             <input type="number" id="children" name="children" min="0" value="{{ old('children', $children) }}" 
                               class="text-sm font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0 w-8 text-center md:text-left">
                        </div>
                    </div>
                    <button type="submit" id="search-btn-desktop" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-xl font-black text-sm transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="search-btn-text">Search Availability</span>
                        <span class="search-btn-loading hidden">Searching...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Responsive Search Bar (Mobile Position) -->
        <div class="md:hidden relative px-4 z-20">
            <form id="search-form-mobile" action="{{ route('booking.index') }}" method="GET" class="bg-white/95 backdrop-blur-md p-6 rounded-3xl shadow-xl flex flex-col gap-6 border border-gray-100">
                <div class="flex flex-col gap-4">
                    <div class="flex-1 border-b border-gray-100 pb-4">
                        <label for="check_in_mobile" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Check-in</label>
                        <input type="date" id="check_in_mobile" name="check_in"  value="{{ old('check_in', $currentDate) }}"  
                               min="{{ date('Y-m-d') }}"
                               class="text-base font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0 cursor-pointer w-full">
                    </div>
                    <div class="flex-1 border-b border-gray-100 pb-4">
                        <label for="check_out_mobile" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Check-out</label>
                        <input type="date" id="check_out_mobile" name="check_out" value="{{ old('check_out', $nextDate) }}" 
                               class="text-base font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0 cursor-pointer w-full">
                    </div>
                    <div class="flex-1 border-b border-gray-100 pb-4">
                        <label for="adults_mobile" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Adults</label>
                        <div class="flex items-center gap-4">
                             <input type="number" id="adults_mobile" name="adults" min="1" value="{{ old('adults', $adults) }}" 
                                   class="text-base font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0 w-8 text-left">
                        </div>
                    </div>
                    <div class="flex-1">
                        <label for="children_mobile" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 block">Children</label>
                        <div class="flex items-center gap-4">
                             <input type="number" id="children_mobile" name="children" min="0" value="{{ old('children', $children) }}" 
                                   class="text-base font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0 w-8 text-left">
                        </div>
                    </div>
                </div>
                <button type="submit" id="search-btn-mobile" class="bg-green-600 hover:bg-green-700 text-white py-5 rounded-2xl font-black text-base transition-all active:scale-95 shadow-lg shadow-green-100 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="search-btn-text">Search Availability</span>
                    <span class="search-btn-loading hidden">Searching...</span>
                </button>
            </form>
        </div>

        <!-- Section Title & Filter (Visual) -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-100 pb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-900">Available Packages</h2>
                <p class="text-gray-500 font-medium">Compare our premium campsites at a glance</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Sort By:</span>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'recommended']) }}" 
                   class="px-4 py-2 {{ $sort === 'recommended' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 border-gray-200 hover:border-green-500' }} border rounded-xl text-sm font-bold shadow-sm transition-colors">
                   Recommended
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" 
                   class="px-4 py-2 {{ $sort === 'price_low' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 border-gray-200 hover:border-green-500' }} border rounded-xl text-sm font-bold shadow-sm transition-colors">
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