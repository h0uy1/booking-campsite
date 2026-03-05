<x-layout>
    <div class="space-y-8 pb-24">
        @if(session('error'))
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif
        <!-- Breadcrumbs -->
        <nav class="flex text-sm font-bold" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('booking.index', ['check_in' => $checkIn, 'check_out' => $checkOut, 'adults' => $adults, 'children' => $children]) }}" class="text-gray-400 hover:text-green-600 transition-colors">Packages</a></li>
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

        <div class="relative rounded-[2.5rem] shadow-2xl h-[400px] md:h-[550px] group bg-gray-100">
            @if($count === 0)
                <div class="w-full h-full bg-green-50 flex flex-col items-center justify-center">
                    <svg class="w-24 h-24 text-green-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-green-600 font-black italic">No images available for this package</p>
                </div>
            @else
                {{-- Carousel Container --}}
                <div x-ref="container" class="w-full h-full relative overflow-hidden">
                        @foreach($galleryImages as $index => $image)
                            <div class="mySlides fade h-full w-full">
                                <img src="{{ $image }}" class="w-full h-full object-contain pointer-events-none" alt="Campsite {{ $index + 1 }}">
                            </div>
                        @endforeach
                        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                        <a class="next" onclick="plusSlides(1)">&#10095;</a>
                </div>
            @endif

            {{-- Floating Badge for Gallery --}}
            <div class="absolute top-6 right-6 z-20">
                <div class="bg-black/20 backdrop-blur-md px-3 py-1.5 rounded-xl text-[10px] font-black text-white shadow-xl border border-white/20 uppercase tracking-widest flex items-center gap-2">
                    <span id="active-photo-index">1</span> / {{ $count }} Photos
                </div>
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
                    <a href="https://www.google.com/maps/place/Tam+Durian+Farm+Campsite%26Cafe/@2.2722739,102.4085363,17z/data=!3m1!4b1!4m6!3m5!1s0x31d1e7a9574f33c1:0xa9fcd93fec35b57c!8m2!3d2.2722739!4d102.4111112!16s%2Fg%2F11rt_k1ck5" 
                       target="_blank" rel="noopener noreferrer" 
                       class="flex items-center text-sm font-bold text-gray-400 mb-6 hover:text-gray-600 transition-all group">
                        <svg class="w-5 h-5 mr-1 text-green-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Tam Durian Farm & Campsite, Jasin, Melaka — <span class="text-blue-600 group-hover:underline">Excellent Location - View on map</span></span>
                    </a>
 
                    <div class="prose prose-sm max-w-none text-gray-500 font-medium leading-relaxed text-justify">
                        <p>Welcome to <strong>Tam Durian Farm & Campsite</strong> - located in the serene countryside of Jasin, Melaka, which offers a unique blend of agricultural charm and outdoor adventure. Our campsite provides an ideal setting for families, friends, and individuals seeking a refreshing escape from urban life.</p>
                        <p>At Tam Durian Farm & Campsite, guests can immerse themselves in the natural beauty of a durian orchard while enjoying modern camping conveniences. The campsite is designed to accommodate both seasoned campers and newcomers, offering pre-set tents and essential amenities to ensure a comfortable stay. Evenings are often enlivened with live music, creating a vibrant atmosphere under the stars.</p>
                        <p>Tam Durian Farm & Campsite is approximately a 30-minute drive from Melaka town, making it conveniently accessible for a weekend getaway. The campsite is pet-friendly, allowing guests to bring along their furry companions with prior notification. Guests are advised to bring mosquito repellent due to the natural orchard setting. The management is noted for their hospitality, ensuring a welcoming and enjoyable experience for all visitors.</p>
                    </div>
                </div>

                <!-- Amenities (Updated Package Offers) -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-black text-gray-900 mb-6">What this package offers</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <!-- Sleep & Comfort -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black text-green-600 uppercase tracking-widest border-b border-green-100 pb-2">Sleep & Comfort</h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center text-green-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Tent, mattresses, pillows, bedsheet</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center text-green-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Table lamp & fan</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center text-green-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Power socket provided</p>
                                </div>
                            </div>
                        </div>

                        <!-- Dining -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest border-b border-blue-100 pb-2">Dining & Meals</h4>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-700">5:30 PM Dinner (Catering)</p>
                                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-tight italic">Limited refill provided</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-700">8:00 PM Dinner (BBQ)</p>
                                        <p class="text-[10px] font-black text-blue-400 uppercase tracking-tight italic">Limited refill provided</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">8:30 AM Breakfast</p>
                                </div>
                                <div class="flex items-center gap-3 border-t border-blue-50 pt-2 mt-2">
                                    <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700 italic">Dining area per group (Eggroll table & chair)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Facilities -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black text-orange-600 uppercase tracking-widest border-b border-orange-100 pb-2">Common Facilities</h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Shared toilet with water heater</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Common area with water dispenser</p>
                                </div>
                            </div>
                        </div>

                        <!-- Essentials -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-black text-purple-600 uppercase tracking-widest border-b border-purple-100 pb-2">Essentials</h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Mineral water per pax</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Ice box provided per group</p>
                                </div>
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
                                Rates shown are inclusive of all local taxes and service charges. Payment processing fee may apply. Check-in is after 3:00 PM and Check-out is before 12:00 PM. 
                                Cancellations are free up to 7 days before arrival.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <div class="sticky top-8 space-y-6">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total for {{ $nights }} nights</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-black text-gray-900">$<span id="display_total_price">{{ number_format($totalPrice, 0) }}</span></span>
                                    <span class="text-sm font-bold text-gray-400 uppercase">/ <span id="display_room_count">1</span> Unit<span id="display_room_suffix"></span></span>
                                </div>
                                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-wider">For {{ $adults }} Adults @if($children > 0), {{ $children }} Children @endif</p>
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
                                     <span id="viewer-count" class="text-sm font-bold text-red-700 italic">... people viewing now</span>
                                     <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                        </div>
                        @if($tent->slots_count > 0)
                            <form action="{{ route('booking.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tent" value="{{ $tent->id}}">
                                <input type="hidden" name="check_in_date" value="{{ $checkIn }}">
                                <input type="hidden" name="check_out_date" value="{{ $checkOut }}">
                                <input type="hidden" name="adults" value="{{ $adults }}">
                                <input type="hidden" name="children" value="{{ $children }}">

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
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 font-black">TD</div>
                        <div>
                            <p class="text-sm font-black text-gray-900">Tam Durian Farm</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Professional Host</p>
                        </div>
                        <button class="ml-auto text-blue-600 font-black text-xs hover:underline">Contact</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

<script>
    function updateViewerCount() {
        fetch('{{ route('booking.viewerCount', $tent->id) }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('viewer-count').innerText = (data.count > 1 ? data.count : '1') + ' people viewing now';
            })
            .catch(error => console.error('Error fetching viewer count:', error));
    }

    // Room Count Selection Logic
    const basePrice = {{ $totalPrice }};
    const roomCountSelect = document.getElementById('room_count_select');
    const displayTotalPrice = document.getElementById('display_total_price');
    const displayRoomCount = document.getElementById('display_room_count');
    const displayRoomSuffix = document.getElementById('display_room_suffix');
    const formRoomCount = document.getElementById('form_room_count');
    const formTotalPrice = document.getElementById('form_total_price');

    if (roomCountSelect) {
        roomCountSelect.addEventListener('change', function() {
            const count = parseInt(this.value);
            const total = basePrice * count;
            
            // Update UI
            displayTotalPrice.innerText = total.toLocaleString();
            displayRoomCount.innerText = count;
            displayRoomSuffix.innerText = count > 1 ? 's' : '';
            
            // Update Form
            formRoomCount.value = count;
            formTotalPrice.value = total;
        });
    }

    // Initial update
    updateViewerCount();

    // Poll every 20 seconds
    setInterval(updateViewerCount, 20000);


let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  if (dots.length > 0) {
    dots[slideIndex-1].className += " active";
  }
  
  // Update photo badge
  const badge = document.getElementById('active-photo-index');
  if (badge) {
    badge.innerText = slideIndex;
  }
}
</script>
