<x-layout>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Edit Tent Details</h1>
                <p class="mt-2 text-lg text-gray-500">Update configuration and pricing for this tent.</p>
            </div>

            <form method="POST" action="/tents/{{ $tent->id }}/update" class="space-y-8" enctype="multipart/form-data">
                @csrf
                
                @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Tent Details Card -->
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            Edit Configuration
                        </h3>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            ID: {{ $tent->id }}
                        </span>
                    </div>
                    
                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 gap-8">
                            <!-- Image Management -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-4">Current Images</label>
                                
                                @if($tent->images->isNotEmpty())
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                        @foreach($tent->images as $image)
                                            <div class="relative group rounded-lg overflow-hidden shadow-sm">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Tent Image" class="w-full h-32 object-cover">
                                                
                                                <label class="absolute inset-0 cursor-pointer">
                                                    <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="peer sr-only">
                                                    
                                                    <!-- Hover/Selection Overlay -->
                                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/60 peer-checked:bg-black/40 transition-all duration-200 flex items-center justify-center">
                                                        <div class="text-white opacity-0 group-hover:opacity-100 peer-checked:opacity-100 flex flex-col items-center transition-opacity duration-200">
                                                            <div class="bg-red-600 p-2 rounded-full mb-2 peer-checked:block hidden">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </div>
                                                            <div class="bg-white/20 p-2 rounded-full mb-2 peer-checked:hidden block">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </div>
                                                            <span class="text-xs font-bold uppercase tracking-wider">Mark for Delete</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Selected Border -->
                                                    <div class="absolute inset-0 border-4 border-red-500 rounded-lg pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity duration-200"></div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 mb-6 italic">* Click trash icon to mark images for removal upon save.</p>
                                @elseif($tent->image)
                                     <div class="mb-6 inline-block relative bg-gray-100 rounded-lg p-2">
                                        <img src="{{ asset('storage/' . $tent->image) }}" alt="Current Image" class="h-40 w-auto object-cover rounded-lg border border-gray-200">
                                        <div class="absolute top-2 right-2 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full shadow-sm">Legacy Image</div>
                                    </div>
                                @endif

                                <label class="block text-sm font-bold text-gray-700 mb-2">Upload New Images</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-green-500 hover:bg-green-50/30 transition-all duration-200 group cursor-pointer relative">
                                    <div class="space-y-1 text-center pointer-events-none">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-green-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <span class="relative bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                                <span>Upload new files</span>
                                            </span>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                    </div>
                                    <input id="new_images" name="new_images[]" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" multiple>
                                </div>
                                
                                <div id="image-preview-container" class="mt-6 hidden grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    <!-- Previews will be injected here -->
                                </div>
                                
                                @error('new_images')
                                    @foreach($errors->get('new_images') as $message)
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @endforeach
                                @enderror
                            </div>

                            <!-- Basic Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-bold text-gray-700">Tent Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name" required value="{{ old('name', $tent->name) }}"
                                        class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                                </div>

                                <div>
                                    <label for="max_capacity" class="block text-sm font-bold text-gray-700">Max Capacity <span class="text-gray-400 font-normal">(People)</span> <span class="text-red-500">*</span></label>
                                    <input type="number" name="max_capacity" id="max_capacity" required min="1" value="{{ old('max_capacity', $tent->max_capacity) }}"
                                        class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                                </div>
                            </div>

                            <!-- Pricing Configuration -->
                            <div class="border-t border-gray-100 pt-6">
                                <label for="pricing_type" class="block text-sm font-bold text-gray-700 mb-2">Pricing Structure <span class="text-red-500">*</span></label>
                                <select id="pricing_type" name="pricing_type" required class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg border">
                                    <option value="" disabled>Select Pricing Type</option>
                                    <option value="person" {{ old('pricing_type', $tent->pricing_type) == 'person' ? 'selected' : '' }}>Per Person (Adult/Child rates)</option>
                                    <option value="base" {{ old('pricing_type', $tent->pricing_type) == 'base' ? 'selected' : '' }}>Base Price (Tiered by capacity)</option>
                                </select>
                            </div>

                            <!-- Base Price Fields -->
                            <div id="base-price-fields" class="space-y-6 {{ old('pricing_type', $tent->pricing_type) == 'base' ? '' : 'hidden' }} bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Capacity Tiers</h4>
                                    <span class="text-xs text-gray-500">Define price based on number of occupants</span>
                                </div>
                                
                                <div id="base-prices-container" class="space-y-4">
                                    @php
                                        // Merge old input with existing prices for consistent display after validation failure
                                        $basePrices = old('base_prices');
                                        if (!$basePrices) {
                                            $basePrices = $tent->pricing_type == 'base' ? $tent->prices->toArray() : [];
                                        }
                                    @endphp

                                    @if((old('pricing_type', $tent->pricing_type) == 'base') && (count($basePrices) > 0))
                                        @foreach($basePrices as $index => $price)
                                            <div class="base-price-row bg-white border border-gray-200 p-5 rounded-xl relative shadow-sm transition-shadow hover:shadow-md">
                                                <div class="absolute -left-3 top-4 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">Tier {{ $index + 1 }}</div>
                                                @if($index > 0)
                                                <button type="button" class="remove-tier-btn absolute top-2 right-2 text-red-400 hover:text-red-700 text-sm font-medium transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                                @endif
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-2">
                                                    <!-- Capacity -->
                                                    <div class="md:col-span-4">
                                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Capacity</label>
                                                        <input type="number" name="base_prices[{{ $index }}][capacity]" min="1" value="{{ $price['capacity'] ?? $price->capacity ?? '' }}"
                                                            class="pl-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('base_prices.'.$index.'.capacity') border-red-500 @enderror" placeholder="People">
                                                        @error('base_prices.'.$index.'.capacity')
                                                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                                        @else
                                                            <p class="mt-1 text-xs text-gray-400">Max people for this price</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Prices -->
                                                    <div class="md:col-span-4">
                                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Weekday ($)</label>
                                                        <div class="relative rounded-md shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">$</span>
                                                            </div>
                                                            <input type="number" name="base_prices[{{ $index }}][price_weekday]" min="0" step="0.01" value="{{ $price['price_weekday'] ?? $price->price_weekday ?? '' }}"
                                                                class="block w-full pl-7 shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('base_prices.'.$index.'.price_weekday') border-red-500 @enderror"
                                                                placeholder="0.00">
                                                        </div>
                                                        @error('base_prices.'.$index.'.price_weekday')
                                                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="md:col-span-4">
                                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Weekend ($)</label>
                                                        <div class="relative rounded-md shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">$</span>
                                                            </div>
                                                            <input type="number" name="base_prices[{{ $index }}][price_weekend]" min="0" step="0.01" value="{{ $price['price_weekend'] ?? $price->price_weekend ?? '' }}"
                                                                class="block w-full pl-7 shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 @error('base_prices.'.$index.'.price_weekend') border-red-500 @enderror"
                                                                placeholder="0.00">
                                                        </div>
                                                        @error('base_prices.'.$index.'.price_weekend')
                                                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Keep initial row structure if needed for JS/empty state same as create -->
                                         <div class="base-price-row bg-white border border-gray-200 p-5 rounded-xl relative shadow-sm transition-shadow hover:shadow-md">
                                            <div class="absolute -left-3 top-4 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">Tier 1</div>
                                            <!-- ... (Same fields as Create but empty) ... -->
                                                 <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-2">
                                                <div class="md:col-span-4">
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Capacity</label>
                                                    <input type="number" name="base_prices[0][capacity]" min="1" required
                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="People">
                                                </div>
                                                <div class="md:col-span-4">
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Weekday ($)</label>
                                                    <div class="relative rounded-md shadow-sm">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm">$</span>
                                                        </div>
                                                        <input type="number" name="base_prices[0][price_weekday]" min="0" step="0.01" required
                                class="block w-full pl-7 shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="0.00">
                                                    </div>
                                                </div>
                                                <div class="md:col-span-4">
                                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Weekend ($)</label>
                                                    <div class="relative rounded-md shadow-sm">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm">$</span>
                                                        </div>
                                                        <input type="number" name="base_prices[0][price_weekend]" min="0" step="0.01" required
                                class="block w-full pl-7 shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <button type="button" id="add-tier-btn" class="w-full inline-flex items-center justify-center px-4 py-2 border-2 border-dashed border-green-300 text-sm font-bold rounded-lg text-green-700 bg-green-50 hover:bg-green-100 hover:border-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Add Another Price Tier
                                </button>
                            </div>

                            <!-- Person Price Fields -->
                            <div id="person-price-fields" class="{{ old('pricing_type', $tent->pricing_type) == 'person' ? '' : 'hidden' }} bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Rates Per Person</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @php
                                        $firstPrice = $tent->prices->first();
                                        $adultPrice = $tent->pricing_type == 'person' && $firstPrice ? $firstPrice->adult_price : '';
                                        $childPrice = $tent->pricing_type == 'person' && $firstPrice ? $firstPrice->child_price : '';
                                    @endphp
                                    <div>
                                        <label for="adult_price" class="block text-sm font-medium text-gray-700">Adult Price</label>
                                        <div class="relative mt-1 rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-lg">$</span>
                                            </div>
                                            <input type="number" name="adult_price" id="adult_price" min="0" step="0.01" value="{{ old('adult_price', $adultPrice) }}"
                                                class="block w-full pl-8 py-3 shadow-sm sm:text-lg border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                                placeholder="0.00">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="child_price" class="block text-sm font-medium text-gray-700">Child Price</label>
                                        <div class="relative mt-1 rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-lg">$</span>
                                            </div>
                                            <input type="number" name="child_price" id="child_price" min="0" step="0.01" value="{{ old('child_price', $childPrice) }}"
                                                class="block w-full pl-8 py-3 shadow-sm sm:text-lg border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                                placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Bar -->
                <div class="flex items-center justify-between pb-8">
                    <!-- Delete Button moved here -->
                    <button type="button" onclick="if(confirm('Are you definitely sure? This will delete the tent and all its images permanently.')) document.getElementById('delete-form').submit();" class="text-red-600 hover:text-red-900 font-medium text-sm flex items-center px-4 py-2 rounded-lg hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete Tent
                    </button>

                    <div class="flex space-x-4">
                        <a href="/tents" class="bg-white py-3 px-6 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-transform transform hover:-translate-y-0.5">
                            Update Changes
                        </button>
                    </div>
                </div>
            </form>

            <!-- Hidden Delete Form -->
            <form id="delete-form" action="/tents/{{ $tent->id }}/delete" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image Previews
        const newImagesInput = document.getElementById('new_images');
        if (newImagesInput) {
            newImagesInput.addEventListener('change', function(event) {
                const container = document.getElementById('image-preview-container');
                container.innerHTML = '';
                container.classList.add('hidden');
                
                if (this.files && this.files.length > 0) {
                    container.classList.remove('hidden');
                    Array.from(this.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'h-32 w-full object-cover rounded-lg border border-gray-200 shadow-sm';
                            container.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });
        }

        const pricingType = document.getElementById('pricing_type');
        const basePriceFields = document.getElementById('base-price-fields');
        const personPriceFields = document.getElementById('person-price-fields');
        const basePricesContainer = document.getElementById('base-prices-container');
        const addTierBtn = document.getElementById('add-tier-btn');
        
        // Person inputs (Adult, Child)
        const personInputs = personPriceFields.querySelectorAll('input');

        function toggleFields() {
            const value = pricingType.value;
            
            // Hide both initially
            basePriceFields.classList.add('hidden');
            personPriceFields.classList.add('hidden');

            // Select all current base inputs
            const allBaseInputs = basePricesContainer.querySelectorAll('input');

            if (value === 'base') {
                basePriceFields.classList.remove('hidden');
                allBaseInputs.forEach(input => input.required = true);
                personInputs.forEach(input => input.required = false);
            } else if (value === 'person') {
                personPriceFields.classList.remove('hidden');
                allBaseInputs.forEach(input => input.required = false);
                personInputs.forEach(input => input.required = true);
            } else {
                allBaseInputs.forEach(input => input.required = false);
                personInputs.forEach(input => input.required = false);
            }
        }

        // Add Tier Logic
        // Calculate initial tier count based on existing rows
        let tierCount = basePricesContainer.querySelectorAll('.base-price-row').length || 1;
        if (tierCount === 0) tierCount = 1; // Safety fallback

        addTierBtn.addEventListener('click', function() {
            const index = tierCount; // Use current count as index (simplified)
            tierCount++;
            
            const newRow = document.createElement('div');
            newRow.className = 'base-price-row bg-white border border-gray-200 p-5 rounded-xl relative shadow-sm transition-shadow hover:shadow-md mt-4';
            newRow.innerHTML = `
                <div class="absolute -left-3 top-4 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">Tier ${tierCount}</div>
                <button type="button" class="remove-tier-btn absolute top-2 right-2 text-red-400 hover:text-red-700 text-sm font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-2">
                    <!-- Capacity -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Capacity</label>
                        <input type="number" name="base_prices[${index}][capacity]" min="1" required
                            class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="People">
                        <p class="mt-1 text-xs text-gray-400">Max people for this price</p>
                    </div>

                    <!-- Prices -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Weekday ($)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="base_prices[${index}][price_weekday]" min="0" step="0.01" required
                                class="block w-full pl-7 shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="0.00">
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Weekend ($)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="base_prices[${index}][price_weekend]" min="0" step="0.01" required
                                class="block w-full pl-7 shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>
            `;
            
            basePricesContainer.appendChild(newRow);
            
            // Add remove event listener
            newRow.querySelector('.remove-tier-btn').addEventListener('click', function() {
                newRow.remove();
            });
        });

        // Add remove handlers for server-rendered rows
        document.querySelectorAll('.remove-tier-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                 this.closest('.base-price-row').remove();
            });
        });

        // Initial run
        toggleFields();

        // Change listener
        pricingType.addEventListener('change', toggleFields);
    });
</script>
