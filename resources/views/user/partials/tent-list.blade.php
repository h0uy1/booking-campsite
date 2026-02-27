<div class="space-y-6" id="tent-list-container">
    @foreach($tents as $tent)
    <div class="group bg-white rounded-2xl md:rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col md:flex-row h-auto md:h-72">

        <!-- Left: Image Section (33%) -->
        <div class="relative w-full md:w-1/3 h-56 md:h-full overflow-hidden">
            @if($tent->images->isNotEmpty())
                <img src="{{ asset('storage/' . $tent->images->first()->image_path) }}"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     alt="{{ $tent->name }}">
            @elseif($tent->image)
                 <img src="{{ asset('storage/' . $tent->image) }}"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     alt="{{ $tent->name }}">
            @else
                <div class="w-full h-full bg-green-50 flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif

            <!-- Image Count Badge -->
            <div class="absolute bottom-4 left-4">
                <span class="px-3 py-1 bg-black/60 backdrop-blur-md rounded-lg text-[10px] font-bold text-white flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $tent->images->count() ?: ($tent->image ? 1 : 0) }} Photos
                </span>
            </div>

            <!-- Heart/Save Icon -->
            <button class="absolute top-4 right-4 p-2 bg-white/20 backdrop-blur-md rounded-full text-white hover:bg-white hover:text-red-500 transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
            </button>
        </div>

        <!-- Middle: Details Section (40%) -->
        <div class="relative p-6 flex-grow flex flex-col justify-between md:border-r md:border-gray-100">
            <div>
                <div class="flex items-center gap-2 mb-2">
                     @for($i = 0; $i < 5; $i++)
                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                     @endfor
                     <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded italic">Agoda Recommended</span>
                </div>

                <h3 class="text-xl md:text-2xl font-black text-gray-900 group-hover:text-green-600 transition-colors mb-1">{{ $tent->name }}</h3>

                <div class="flex items-center text-xs font-bold text-gray-400 mb-4">
                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Tam Durian Farm & Campsite <a href="https://www.google.com/maps/place/Tam+Durian+Farm+Campsite%26Cafe/@2.2722739,102.4085363,17z/data=!3m1!4b1!4m6!3m5!1s0x31d1e7a9574f33c1:0xa9fcd93fec35b57c!8m2!3d2.2722739!4d102.4111112!16s%2Fg%2F11rt_k1ck5" target="_blank" rel="noopener noreferrer" class="text-blue-500 ml-1 hover:underline cursor-pointer">Show on map</a>
                </div>

                <!-- Amenities (Sync with show page) -->
                <div class="flex flex-wrap gap-x-3 gap-y-1.5 mb-4">
                    <!-- Sleep -->
                    <div class="flex items-center gap-1 border border-green-100 bg-green-50/30 px-2 py-0.5 rounded-lg">
                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-[9px] font-bold text-gray-600 whitespace-nowrap">Tent & Bedding</span>
                    </div>
                    <!-- Meals -->
                    <div class="flex items-center gap-1 border border-blue-100 bg-blue-50/30 px-2 py-0.5 rounded-lg">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-[9px] font-bold text-gray-600 whitespace-nowrap">3 Meals (BBQ/Catering/Breakfast)</span>
                    </div>
                    <div class="flex items-center gap-1 border border-blue-100 bg-blue-50/30 px-2 py-0.5 rounded-lg">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[9px] font-bold text-blue-600 italic whitespace-nowrap">Limited Refills</span>
                    </div>
                    <!-- Facilities -->
                    <div class="flex items-center gap-1 border border-orange-100 bg-orange-50/30 px-2 py-0.5 rounded-lg">
                        <svg class="w-3 h-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-[9px] font-bold text-gray-600 whitespace-nowrap">Hot Shower</span>
                    </div>
                    <div class="flex items-center gap-1 border border-purple-100 bg-purple-50/30 px-2 py-0.5 rounded-lg">
                        <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-[9px] font-bold text-gray-600 whitespace-nowrap">Ice Box & Water</span>
                    </div>
                </div>
            </div>

            <div class="text-xs font-bold text-green-700 bg-green-50 border border-green-100 px-3 py-2 rounded-xl inline-flex items-center gap-2 self-start ring-4 ring-green-50 ring-opacity-50">
                <span class="flex h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                Great for groups up to {{ $tent->max_capacity }} people
            </div>
        </div>

        <!-- Right: Pricing Section (27%) -->
        <div class="w-full md:w-1/4 bg-gray-50 p-6 flex flex-col justify-end items-end text-right border-t md:border-t-0 border-gray-100">
            <div class="mb-auto">
                <div class="flex items-center gap-2 justify-end mb-1">
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Exceptional</div>
                    <div class="bg-blue-600 text-white rounded-lg flex items-center justify-center font-black p-1.5 text-sm leading-none shadow-lg shadow-blue-100">9.2</div>
                </div>
                <p class="text-[10px] text-gray-400 font-bold italic">Based on 124 reviews</p>
            </div>

            <div class="space-y-1 w-full">
                <p class="text-[10px] font-black text-red-500 uppercase tracking-widest animate-pulse">Only {{ $tent->slots_count }} units left!</p>

                <div class="flex flex-col">
                    <span class="text-[10px] font-extrabold text-gray-400 line-through tracking-wider">
                        @if($tent->prices->isNotEmpty())
                            ${{ number_format($tent->prices->min('price_weekday') * 1.2, 0) }}
                        @endif
                    </span>
                    <div class="flex items-baseline justify-end gap-1">
                        <span class="text-xs font-black text-gray-400 uppercase">From</span>
                        @if($tent->pricing_type == 'person')
                            @php $price = $tent->prices->first(); @endphp
                            <span class="text-3xl font-black text-gray-900">${{ $price ? number_format($price->adult_price, 0) : '0' }}</span>
                        @else
                            <span class="text-3xl font-black text-gray-900">${{ $tent->prices->isNotEmpty() ? number_format($tent->prices->min('price_weekday'), 0) : '0' }}</span>
                        @endif
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">per night / inclusive of all taxes</p>
                </div>

                <a href="{{ route('booking.show', [
                    'id' => $tent->id,
                    'check_in' => $currentDate,
                    'check_out' => $nextDate,
                    'adults' => $adults,
                    'children' => $children,
                    ]) }}" 
                    class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white py-4 rounded-xl font-black text-sm transition-all shadow-xl shadow-green-100 group/btn flex items-center justify-center text-center">
                    See Choices
                    <svg class="w-5 h-5 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>
    @endforeach

    @if($tents->isEmpty())
    <div class="bg-white rounded-[3rem] p-24 text-center border-2 border-dashed border-gray-100">
        <div class="bg-gray-50 h-32 w-32 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
        </div>
        <h3 class="text-3xl font-black text-gray-900 mb-4">No results for your search</h3>
        <p class="text-gray-400 font-medium max-w-sm mx-auto">We're currently refilling our campsite inventory. Please check back later for new available packages!</p>
    </div>
    @endif
</div>
