<x-layout>
@php
    $startDate = \Carbon\Carbon::parse($checkIn);
    $endDate = \Carbon\Carbon::parse($checkOut);
    $priceBreakdown = [];

    if ($tent->pricing_type == 'person') {
        $price = $tent->prices->first();
        $totalAdultPrice = ($price->adult_price ?? 0) * $adults * $nights;
        $totalChildPrice = ($price->child_price ?? 0) * $children * $nights;

        $priceBreakdown[] = [
            'label' => "$adults Adults x RM" . number_format($price->adult_price ?? 0, 0) . " x $nights nights",
            'amount' => $totalAdultPrice
        ];

        if ($children > 0 && ($price->child_price ?? 0) > 0) {
            $priceBreakdown[] = [
                'label' => "$children Children x RM" . number_format($price->child_price ?? 0, 0) . " x $nights nights",
                'amount' => $totalChildPrice
            ];
        }

    } else {
        $matchedPrice = $tent->prices->where('capacity', '=', $adults)->first();
        if (!$matchedPrice) {
            $matchedPrice = $tent->prices->sortByDesc('capacity')->first();
        }
        
        $baseTotal = 0;
        $childTotal = 0;

        for ($i = 0; $i < $nights; $i++) {
            $currentDay = $startDate->copy()->addDays($i);
            $isWeekend = in_array($currentDay->dayOfWeek, [\Carbon\Carbon::SATURDAY]);
            
            if ($isWeekend) {
                $dailyPrice = $matchedPrice->price_weekend ?? 0;
            } else {
                $dailyPrice = $matchedPrice->price_weekday ?? 0;
            }

            $baseTotal += $dailyPrice;

            if ($children > 0 && isset($matchedPrice->child_price) && $matchedPrice->child_price > 0) {
                $childTotal += ($matchedPrice->child_price * $children);
            }
        }
        
        $priceBreakdown[] = [
            'label' => "Base Price ($adults Adults) for $nights nights",
            'amount' => $baseTotal
        ];

        if ($childTotal > 0) {
            $priceBreakdown[] = [
                'label' => "Child Surcharge ($children Children x RM" . number_format($matchedPrice->child_price ?? 0, 0) . " x $nights nights)",
                'amount' => $childTotal
            ];
        }
    }
@endphp
    <script src="https://js.stripe.com/v3/"></script>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <div class="mb-6">
            <a href="{{ route('booking.show', ['id' => $tent->id, 'check_in' => $checkIn, 'check_out' => $checkOut, 'adults' => $adults, 'children' => $children]) }}" class="inline-flex items-center text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to {{ $tent->name }} Details
            </a>
        </div>
        <form action="{{ route('booking.checkout') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="tent" value="{{ $tent->id }}">
            <input type="hidden" name="check_in_date" value="{{ $checkIn }}">
            <input type="hidden" name="check_out_date" value="{{ $checkOut }}">
            <input type="hidden" name="adults" value="{{ $adults }}">
            <input type="hidden" name="children" value="{{ $children }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Forms -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Room Details Block -->
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-stone-200">
                        @php
                            $imageUrl = null;
                            if ($tent->images && $tent->images->isNotEmpty()) {
                                $imageUrl = asset('storage/' . $tent->images->first()->image_path);
                            } elseif ($tent->image) {
                                $imageUrl = asset('storage/' . $tent->image);
                            }
                        @endphp
                        
                        @if($imageUrl)
                            <div class="w-full object-cover"></div>
                        @endif

                        <div class="p-6 md:p-8">
                            <h2 class="text-xl font-medium tracking-wide text-stone-900 mb-6 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-stone-50 flex items-center justify-center border border-stone-100 text-stone-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                </div>
                                {{ $tent->name }}
                            </h2>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-stone-50 rounded-xl p-4 border border-stone-100 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-stone-200 flex flex-col items-center justify-center text-stone-600 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-0.5">Check In</p>
                                        <p class="text-sm font-medium text-stone-900">{{ \Carbon\Carbon::parse($checkIn)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="bg-stone-50 rounded-xl p-4 border border-stone-100 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-stone-200 flex items-center justify-center text-stone-600 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-0.5">Check Out</p>
                                        <p class="text-sm font-medium text-stone-900">{{ \Carbon\Carbon::parse($checkOut)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Details Block -->
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-stone-200">
                        <h3 class="text-base font-semibold tracking-wide text-stone-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Guest Details <span class="text-red-500">*</span>
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="first_name" class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-2">First Name</label>
                                <input type="text" id="first_name" name="customer_name" class="w-full bg-stone-50 border border-stone-200 text-stone-900 text-sm rounded-xl focus:ring-stone-500 focus:border-stone-500 block p-3.5 transition-colors" placeholder="First Name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div>
                                <label for="last_name" class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-2">Last Name</label>
                                <input type="text" id="last_name" name="customer_last_name" class="w-full bg-stone-50 border border-stone-200 text-stone-900 text-sm rounded-xl focus:ring-stone-500 focus:border-stone-500 block p-3.5 transition-colors" placeholder="Last Name">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-2">Adults</label>
                                <div class="w-full bg-stone-100 border border-stone-200 text-stone-600 font-medium text-sm rounded-xl p-3.5">
                                    {{ $adults }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-2">Children</label>
                                <div class="w-full bg-stone-100 border border-stone-200 text-stone-600 font-medium text-sm rounded-xl p-3.5">
                                    {{ $children }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="special_request" class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-2">Special Request</label>
                            <input type="text" id="special_request" name="customer_address" class="w-full bg-stone-50 border border-stone-200 text-stone-900 text-sm rounded-xl focus:ring-stone-500 focus:border-stone-500 block p-3.5 transition-colors" placeholder="Any special requests?">
                        </div>
                    </div>

                    <!-- Contact Details Block -->
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-stone-200">
                        <h3 class="text-base font-semibold tracking-wide text-stone-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Contact Details
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="mobile" class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-2">Mobile Number <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 text-sm font-medium text-stone-500 bg-stone-100 border border-r-0 border-stone-200 rounded-l-xl">
                                        +60
                                    </span>
                                    <input type="text" id="mobile" name="customer_phone" class="rounded-none rounded-r-xl bg-stone-50 border border-stone-200 text-stone-900 focus:ring-stone-500 focus:border-stone-500 block flex-1 min-w-0 w-full text-sm p-3.5 transition-colors" placeholder="123456789" required>
                                </div>
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-semibold text-stone-500 uppercase tracking-widest mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="customer_email" class="w-full bg-stone-50 border border-stone-200 text-stone-900 text-sm rounded-xl focus:ring-stone-500 focus:border-stone-500 block p-3.5 transition-colors" placeholder="example@email.com" value="{{ auth()->user()->email }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information Block -->
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-stone-200">
                        <h3 class="text-base font-semibold tracking-wide text-stone-900 mb-6 flex items-center gap-2">
                            <div class="w-10 h-6 bg-stone-100 border border-stone-200 rounded flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-stone-700" viewBox="0 0 40 40"><path d="M20,40C8.95,40,0,31.05,0,20C0,8.95,8.95,0,20,0c11.05,0,20,8.95,20,20C40,31.05,31.05,40,20,40z" fill="#f0f0f0"/><path d="M22.84,17.45c0-1.42-1.32-2-3.13-2c-2.42,0-4.39,0.92-4.39,0.92l-0.78-3.41c0,0,2.23-1.09,5.55-1.09 c3.98,0,6.68,1.86,6.68,5.43c0,4.86-5.83,5.32-5.83,7.21c0,1.21,1.52,1.83,3.74,1.83c1.89,0,4.02-0.84,4.02-0.84l0.87,3.58 c0,0-2.34,1.06-5.26,1.06c-4.48,0-7.21-2.14-7.21-5.74C17.1,19.3,22.84,18.96,22.84,17.45z" fill="#6772E5"/></svg>
                            </div>
                            Payment Information
                        </h3>
                        
                        <div class="mb-2 bg-stone-50 px-4 py-3.5 border border-stone-200 rounded-xl shadow-sm focus-within:ring-2 focus-within:ring-stone-500 focus-within:border-stone-500 transition-colors">
                            <div id="card-element"></div>
                        </div>
                        <div id="card-errors" role="alert" class="text-sm text-red-600 mb-2 font-medium"></div>
                        <p class="text-xs text-stone-500 mt-2 flex items-center gap-1.5 break-words">
                            <svg class="w-4 h-4 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Your payment data is securely processed and encrypted by Stripe.
                        </p>
                    </div>

                    <!-- Extras Block -->
                    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-stone-200">
                        <h3 class="text-base font-semibold tracking-wide text-stone-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Enhance Your Stay (Optional Extras)
                        </h3>
                        
                        <div class="space-y-4">
                            <label class="flex items-center justify-between p-4 border border-stone-200 rounded-xl cursor-pointer hover:bg-stone-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" name="extras[]" value="Pet Birthday Deco" class="w-5 h-5 text-stone-900 border-stone-300 rounded focus:ring-stone-900">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900">Pet Birthday Decoration</p>
                                        <p class="text-xs text-stone-500 mt-0.5">Special decoration setup for your pet's birthday.</p>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center justify-between p-4 border border-stone-200 rounded-xl cursor-pointer hover:bg-stone-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" name="extras[]" value="Celebration Cake" class="w-5 h-5 text-stone-900 border-stone-300 rounded focus:ring-stone-900">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900">Celebration Cake</p>
                                        <p class="text-xs text-stone-500 mt-0.5">A customized cake for your special occasion.</p>
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center justify-between p-4 border border-stone-200 rounded-xl cursor-pointer hover:bg-stone-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" name="extras[]" value="Extra Bed" class="w-5 h-5 text-stone-900 border-stone-300 rounded focus:ring-stone-900">
                                    <div>
                                        <p class="text-sm font-medium text-stone-900">Extra Bed / Mattress</p>
                                        <p class="text-xs text-stone-500 mt-0.5">Additional comfort for extra guests.</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sidebar Cart -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 bg-white rounded-2xl p-6 md:p-8 shadow-xl border border-stone-200">
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-stone-100">
                            <h3 class="text-base font-semibold tracking-wide text-stone-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Booking Details
                            </h3>
                        </div>

                        <div class="mb-6 flex items-start gap-4 pb-6 border-b border-stone-100">
                            @if($imageUrl ?? false)
                                <div class="w-16 h-16 rounded-xl overflow-hidden shadow-sm shrink-0">
                                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-16 h-16 rounded-xl bg-stone-100 flex items-center justify-center shrink-0 border border-stone-200">
                                    <svg class="w-6 h-6 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <p class="text-base font-medium text-stone-900 leading-tight">{{ $tent->name }}</p>
                                <p class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mt-1">Tam Durian Farm</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-6 bg-stone-50 p-4 rounded-xl border border-stone-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-stone-200 flex items-center justify-center text-stone-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-0.5">Check In</p>
                                    <p class="text-xs font-semibold text-stone-800">{{ \Carbon\Carbon::parse($checkIn)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="h-8 w-px bg-stone-200"></div>
                            <div class="flex items-center gap-3 text-right">
                                <div>
                                    <p class="text-[10px] font-semibold text-stone-400 uppercase tracking-widest mb-0.5">Check Out</p>
                                    <p class="text-xs font-semibold text-stone-800">{{ \Carbon\Carbon::parse($checkOut)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Price Toggle -->
                        <div class="flex items-center justify-between mb-6 cursor-pointer group" onclick="var el = document.getElementById('priceBreakdown'); var icon = document.getElementById('breakdownIcon'); if(el.classList.contains('hidden')){ el.classList.remove('hidden'); icon.classList.add('rotate-180'); } else { el.classList.add('hidden'); icon.classList.remove('rotate-180'); }">
                            <div class="flex items-center gap-2 text-stone-600 bg-stone-100 px-3 py-1.5 rounded-lg text-sm font-medium">
                                <span>{{ $adults }} Adults</span>
                                @if($children > 0)
                                    <span class="w-1 h-1 rounded-full bg-stone-400"></span>
                                    <span>{{ $children }} Children</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-stone-900 group-hover:text-stone-600 transition-colors">
                                <span class="text-base font-medium">MYR {{ number_format($totalPrice, 0) }}</span>
                                <svg id="breakdownIcon" class="w-4 h-4 text-stone-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <!-- Price Breakdown Display -->
                        <div id="priceBreakdown" class="hidden mb-6 p-5 bg-stone-50 rounded-xl border border-stone-200">
                             <p class="text-[10px] font-semibold text-stone-500 uppercase tracking-widest mb-4">Price Breakdown</p>
                             <div class="space-y-3">
                                 @foreach($priceBreakdown as $item)
                                 <div class="flex items-center justify-between">
                                     <span class="text-sm font-medium text-stone-600">{{ $item['label'] }}</span>
                                     <span class="text-sm font-semibold text-stone-900">RM{{ number_format($item['amount'], 0) }}</span>
                                 </div>
                                 @endforeach
                             </div>
                        </div>




                        <div class="mb-6">
                            <label class="flex items-start gap-3 w-full">
                                <input type="checkbox" required class="mt-1 w-4 h-4 text-stone-900 border-stone-300 rounded focus:ring-stone-900">
                                <span class="text-xs font-medium text-stone-600 leading-relaxed max-w-[90%]">
                                    By booking, you have agreed to our <a href="#" class="text-stone-900 underline hover:text-stone-700">Terms and Conditions</a> | <a href="#" class="text-stone-900 underline hover:text-stone-700">Payment Terms</a>
                                </span>
                            </label>
                        </div>
                        

                        <button type="submit" id="submit-btn" class="w-full bg-stone-900 hover:bg-stone-800 text-white py-4 px-6 rounded-xl font-medium tracking-wide text-base transition-all shadow-lg flex items-center justify-center disabled:opacity-75 disabled:cursor-not-allowed">
                            <svg id="spinner" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span id="submit-text">Pay Now</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var stripe = Stripe('{{ config('services.stripe.public') }}');
            var elements = stripe.elements();

            var style = {
                base: {
                    color: '#1c1917',
                    fontFamily: '"Inter", sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#a8a29e'
                    }
                },
                invalid: {
                    color: '#dc2626',
                    iconColor: '#dc2626'
                }
            };

            var card = elements.create('card', {style: style, hidePostalCode: true});
            card.mount('#card-element');

            card.on('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            var form = document.getElementById('checkout-form');
            var submitBtn = document.getElementById('submit-btn');
            var submitText = document.getElementById('submit-text');
            var spinner = document.getElementById('spinner');

            var clientSecret = '{{ $intent->client_secret }}';
            var cardHolderName = document.getElementById('ic_name'); // Can pull from "Enhance your stay" or Auth if needed, keeping simple

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                if (submitBtn.disabled) return;
                
                submitBtn.disabled = true;
                submitText.textContent = 'Processing...';
                spinner.classList.remove('hidden');

                stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: card,
                            billing_details: {
                                name: cardHolderName ? cardHolderName.value : '{{ auth()->user()->name }}',
                                email: '{{ auth()->user()->email }}'
                            }
                        }
                    }
                ).then(function(result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                        
                        submitBtn.disabled = false;
                        submitText.textContent = 'Pay Now';
                        spinner.classList.add('hidden');
                    } else {
                        var hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'payment_method_id');
                        hiddenInput.setAttribute('value', result.setupIntent.payment_method);
                        form.appendChild(hiddenInput);

                        form.submit();
                    }
                });
            });
        });
    </script>
</x-layout>