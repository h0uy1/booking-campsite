<x-layout>
    <div class="space-y-8 pb-24">
        <!-- Breadcrumbs -->
        <nav class="flex text-sm font-bold" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('booking.index') }}" class="text-gray-400 hover:text-green-600 transition-colors">Packages</a></li>
                <li><svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                <li class="text-gray-600">{{ $tent->name }}</li>
            </ol>
        </nav>

        <!-- Image Gallery (Dynamic Layout) -->
        @php
            $galleryImages = collect();
            if ($tent->images->isNotEmpty()) {
                foreach ($tent->images as $img) {
                    $galleryImages->push(asset('storage/' . $img->image_path));
                }
            } elseif ($tent->image) {
                $galleryImages->push(asset('storage/' . $tent->image));
            }
            $count = $galleryImages->count();

            // Price Calculation
            $startDate = \Carbon\Carbon::parse($checkIn);
            $endDate = \Carbon\Carbon::parse($checkOut);
            $nights = $startDate->diffInDays($endDate) ?: 1;
            
            $totalPrice = 0;
            if ($tent->pricing_type == 'person') {
                $price = $tent->prices->first();
                $totalAdultPrice = ($price->adult_price ?? 0) * $adults * $nights;
                $totalChildPrice = ($price->child_price ?? 0) * $children * $nights;
                $totalPrice = $totalAdultPrice + $totalChildPrice;
            } else {
                $matchedPrice = $tent->prices->where('capacity', '=', $adults)->first();
                if (!$matchedPrice) {
                    $matchedPrice = $tent->prices->sortByDesc('capacity')->first();
                }
                
                // Calculate price night by night (Fri & Sat are weekends)
                for ($i = 0; $i < $nights; $i++) {
                    $currentDay = $startDate->copy()->addDays($i);
                    $isWeekend = in_array($currentDay->dayOfWeek, [\Carbon\Carbon::SATURDAY]);
                    
                    if ($isWeekend) {
                        $dailyPrice = $matchedPrice->price_weekend ?? 0;
                    } else {
                        $dailyPrice = $matchedPrice->price_weekday ?? 0;
                    }

                    // Add child surcharge
                    if ($children > 0 && isset($matchedPrice->child_price) && $matchedPrice->child_price > 0) {
                        $dailyPrice += ($matchedPrice->child_price * $children);
                    }

                    $totalPrice += $dailyPrice;
                }
            }
        @endphp

        <div class="relative rounded-[2.5rem] overflow-hidden shadow-2xl h-[400px] md:h-[550px] group bg-gray-100">
            @if($count === 0)
                <div class="w-full h-full bg-green-50 flex flex-col items-center justify-center">
                    <svg class="w-24 h-24 text-green-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-green-600 font-black italic">No images available for this package</p>
                </div>
            @elseif($count === 1)
                {{-- Single image: Full width --}}
                <div class="w-full h-full overflow-hidden">
                    <img src="{{ $galleryImages[0] }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" alt="Campsite">
                </div>
            @elseif($count === 2)
                {{-- Two images: Side by side --}}
                <div class="grid grid-cols-2 gap-2 h-full">
                    <img src="{{ $galleryImages[0] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite 1">
                    <img src="{{ $galleryImages[1] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite 2">
                </div>
            @elseif($count === 3)
                {{-- Three images: 1 Large + 2 Stacked --}}
                <div class="grid grid-cols-3 gap-2 h-full">
                    <div class="col-span-2 overflow-hidden">
                        <img src="{{ $galleryImages[0] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite Main">
                    </div>
                    <div class="grid grid-rows-2 gap-2">
                        <img src="{{ $galleryImages[1] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite 2">
                        <img src="{{ $galleryImages[2] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite 3">
                    </div>
                </div>
            @else
                {{-- 4+ images: Agoda Style Grid --}}
                <div class="grid grid-cols-4 grid-rows-2 gap-2 h-full">
                    <div class="col-span-2 row-span-2 overflow-hidden">
                        <img src="{{ $galleryImages[0] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite main">
                    </div>
                    <div class="overflow-hidden">
                        <img src="{{ $galleryImages[1] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite 2">
                    </div>
                    <div class="overflow-hidden">
                        <img src="{{ $galleryImages[2] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite 3">
                    </div>
                    <div class="overflow-hidden">
                        <img src="{{ $galleryImages[3] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite 4">
                    </div>
                    <div class="relative overflow-hidden">
                        @if($count > 4)
                            <img src="{{ $galleryImages[4] }}" class="w-full h-full object-cover brightness-50" alt="More photos">
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-white font-black text-xl pointer-events-none">
                                <span>+{{ $count - 4 }}</span>
                                <span class="text-[10px] uppercase tracking-[0.2em]">Photos</span>
                            </div>
                        @else
                           <img src="{{ $galleryImages[3] }}" class="w-full h-full object-cover hover:brightness-110 transition-all duration-500" alt="Campsite 4">
                        @endif
                    </div>
                </div>
            @endif

            {{-- Floating Badge for Gallery --}}
            <div class="absolute bottom-6 right-6">
                <button class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-xl text-xs font-black text-gray-900 shadow-xl border border-white hover:bg-white transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    View All Photos
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Details -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        <span class="text-xs font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded italic">Top Quality Experience</span>
                    </div>

                    <h1 class="text-4xl font-black text-gray-900 mb-2">{{ $tent->name }}</h1>
                    <div class="flex items-center text-sm font-bold text-gray-400 mb-6">
                        <svg class="w-5 h-5 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Pine Valley Forest, Oregon — <span class="text-blue-600 hover:underline cursor-pointer">Excellent Location - View on map</span>
                    </div>

                    <div class="prose prose-sm max-w-none text-gray-500 font-medium leading-relaxed">
                        <p>This premium camping package offers the ultimate immersion in nature without sacrificing comfort. Located in a secluded cluster of the Pine Valley Forest, you'll enjoy breathtaking views and peaceful nights under the stars.</p>
                        <p>Perfect for families, groups, or romantic getaways, our {{ $tent->name }} setup includes everything you need for an unforgettable adventure. Maximum capacity is for {{ $tent->max_capacity }} people.</p>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-black text-gray-900 mb-6">What this package offers</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.252-3.905 14.157 0m-11.314-2.828c2.343-2.344 6.142-2.344 8.485 0"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-900 leading-none">High-Speed WiFi</p>
                                <p class="text-xs font-bold text-gray-400 mt-1">Stay connected in nature</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-900 leading-none">Solar Power</p>
                                <p class="text-xs font-bold text-gray-400 mt-1">Sustainable energy for devices</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.985-5.5.5-.1 1.5 0 2 .5 2.5 2.5 2.015 5.239 2.015 5.239 0 0 .5-1.5.5-2.5 0 0 1.5 1.5 1.5 3.5a6 6 0 01-1.5 3.5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-900 leading-none">BBQ Facilities</p>
                                <p class="text-xs font-bold text-gray-400 mt-1">Professional grill station</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-900 leading-none">Stargazing Deck</p>
                                <p class="text-xs font-bold text-gray-400 mt-1">Clear views of the night sky</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Pricing Section -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-black text-gray-900 mb-6">Pricing Information</h3>
                    
                    @if($tent->pricing_type == 'person')
                        <div class="space-y-4">
                            @foreach($tent->prices as $price)
                                <div class="flex items-center justify-between p-6 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div>
                                        <p class="text-lg font-black text-gray-900">Per Person Rate</p>
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Includes all campsite fees</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center justify-end gap-2 text-sm font-bold text-gray-600 mb-1">
                                            <span>Adult: <span class="text-gray-900 text-base font-black">${{ number_format($price->adult_price, 0) }}</span></span>
                                            <span class="text-gray-200">|</span>
                                            <span>Child: <span class="text-gray-900 text-base font-black">${{ number_format($price->child_price, 0) }}</span></span>
                                        </div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase">per night</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Based Price: Show list of prices for capacities -->
                        <div class="overflow-hidden rounded-2xl border border-gray-100">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Group Capacity</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Weekday Rate</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Weekend Rate</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($tent->prices->sortBy('min_capacity') as $price)
                                    <tr class="hover:bg-green-50/10 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs">
                                                    {{ $price->capacity }}
                                                </span>
                                                <span class="text-sm font-bold text-gray-700">Persons</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-lg font-black text-gray-900">${{ number_format($price->price_weekday, 0) }}</span>
                                            <span class="text-[10px] font-black text-gray-400 uppercase">/ night</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-lg font-black text-green-600">${{ number_format($price->price_weekend, 0) }}</span>
                                            <span class="text-[10px] font-black text-gray-400 uppercase">/ night</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-8 p-6 bg-blue-50 rounded-2xl border border-blue-100 flex items-start gap-4">
                        <div class="text-blue-500 mt-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-black text-blue-900">Information about your stay</p>
                            <p class="text-xs font-medium text-blue-700 mt-1 leading-relaxed">
                                Rates shown are inclusive of all local taxes and service charges. Check-in is after 2:00 PM and Check-out is before 11:00 AM. 
                                Cancellations are free up to 48 hours before arrival.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100 sticky top-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total for {{ $nights }} nights</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-gray-900">${{ number_format($totalPrice, 0) }}</span>
                                <span class="text-sm font-bold text-gray-400 uppercase">/ {{ $adults }} Adults @if($children > 0), {{ $children }} Children @endif</span>
                            </div>
                            @if($tent->pricing_type == 'base' && $children > 0 && isset($matchedPrice->child_price) && $matchedPrice->child_price > 0)
                                <p class="text-[10px] font-bold text-blue-600 mt-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Includes ${{ number_format($matchedPrice->child_price, 0) }} surcharge per child/night
                                </p>
                            @endif
                        </div>
                        <div class="bg-blue-600 text-white p-3 rounded-2xl shadow-lg shadow-blue-100 flex flex-col items-center justify-center">
                            <span class="text-xl font-black leading-none">9.2</span>
                            <span class="text-[8px] font-black uppercase tracking-tighter mt-1">Excellent</span>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        <!-- Stay Dates -->
                        <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100/50">
                             <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">Your Stay</p>
                             <div class="flex items-center justify-between">
                                 <div class="flex flex-col">
                                     <span class="text-[10px] font-black text-gray-400 uppercase">Check-in</span>
                                     <span class="text-sm font-black text-gray-900">{{ \Carbon\Carbon::parse($checkIn)->format('D, M d, Y') }}</span>
                                 </div>
                                 <svg class="w-4 h-4 text-blue-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                 <div class="flex flex-col text-right">
                                     <span class="text-[10px] font-black text-gray-400 uppercase">Check-out</span>
                                     <span class="text-sm font-black text-gray-900">{{ \Carbon\Carbon::parse($checkOut)->format('D, M d, Y') }}</span>
                                 </div>
                             </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                             <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Campsite Availability</p>
                             <div class="flex items-center justify-between">
                                 <span class="text-sm font-bold text-gray-700">{{ $tent->slots_count }} Units Available</span>
                                 <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                             </div>
                        </div>
                        <div class="p-4 bg-red-50 rounded-2xl border border-red-100">
                             <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">Urgency</p>
                             <div class="flex items-center justify-between">
                                 <span class="text-sm font-bold text-red-700 italic">8 people viewing now</span>
                                 <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                             </div>
                        </div>
                    </div>
                    @if($tent->slots_count > 0)
                        <form action="{{ route('booking.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="slot_id" value="{{ $tent->slots->first()->id }}">
                            <input type="hidden" name="check_in_date" value="{{ $checkIn }}">
                            <input type="hidden" name="check_out_date" value="{{ $checkOut }}">
                            <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                            
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-5 rounded-[2rem] font-black text-lg transition-all shadow-xl shadow-green-100 group/btn flex items-center justify-center mb-6">
                                Reserve Now
                                <svg class="w-6 h-6 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </form>
                    @else
                        <div class="bg-red-50 border border-red-100 p-6 rounded-[2rem] text-center mb-6">
                            <p class="text-red-700 font-black text-lg mb-1">Fully Booked</p>
                            <p class="text-red-600 text-xs font-bold uppercase tracking-widest">No units available for these dates</p>
                        </div>
                        <a href="{{ route('booking.index') }}" class="w-full bg-gray-900 text-white py-5 rounded-[2rem] font-black text-lg transition-all flex items-center justify-center mb-6 hover:bg-black">
                            Search other dates
                        </a>
                    @endif

                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-xs font-bold text-gray-500">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Instant confirmation
                        </li>
                        <li class="flex items-center gap-3 text-xs font-bold text-gray-500">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Secure payment processing
                        </li>
                        <li class="flex items-center gap-3 text-xs font-bold text-gray-500">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Best price guarantee
                        </li>
                    </ul>
                </div>

                <!-- Host Card (Visual) -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-black">PV</div>
                    <div>
                        <p class="text-sm font-black text-gray-900">Pine Valley Resorts</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Professional Host</p>
                    </div>
                    <button class="ml-auto text-blue-600 font-black text-xs hover:underline">Contact</button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
