<x-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    Find Your Perfect Escape
                </h2>
                <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                    Discover our range of luxury tents designed for comfort and adventure.
                </p>
                <div class="mt-8">
                    <a href="/tents/create" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add New Tent
                    </a>
                </div>
            </div>

            <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach($tents as $tent)
                    <div class="flex flex-col rounded-2xl shadow-lg overflow-hidden bg-white hover:shadow-2xl transition-shadow duration-300 transform hover:-translate-y-1">
                        <!-- Image Carousel/Hero -->
                        <div class="flex-shrink-0 relative overflow-hidden h-64 group bg-gray-100">
                            @if($tent->images->isNotEmpty())
                                <div class="flex overflow-x-auto snap-x snap-mandatory h-full w-full scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                                    @foreach($tent->images as $image)
                                        <div class="snap-center flex-shrink-0 w-full h-full relative">
                                            <img class="h-full w-full object-cover" src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $tent->name }}">
                                        </div>
                                    @endforeach
                                </div>
                                @if($tent->images->count() > 1)
                                    <!-- Indicators -->
                                    <div class="absolute bottom-3 left-0 right-0 flex justify-center space-x-2">
                                        @foreach($tent->images as $index => $image)
                                            <div class="h-1.5 w-1.5 rounded-full bg-white shadow-sm opacity-70"></div>
                                        @endforeach
                                    </div>
                                    <!-- Controls -->
                                    <div class="absolute inset-y-0 left-0 flex items-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button class="bg-black bg-opacity-30 hover:bg-opacity-50 text-white p-2 ml-2 rounded-full focus:outline-none backdrop-blur-sm" onclick="this.parentElement.previousElementSibling.previousElementSibling.scrollBy({left: -320, behavior: 'smooth'})">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                        </button>
                                    </div>
                                    <div class="absolute inset-y-0 right-0 flex items-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button class="bg-black bg-opacity-30 hover:bg-opacity-50 text-white p-2 mr-2 rounded-full focus:outline-none backdrop-blur-sm" onclick="this.parentElement.previousElementSibling.previousElementSibling.previousElementSibling.scrollBy({left: 320, behavior: 'smooth'})">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </button>
                                    </div>
                                @endif
                            @elseif($tent->image)
                                <img class="h-full w-full object-cover" src="{{ asset('storage/' . $tent->image) }}" alt="{{ $tent->name }}">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <svg class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                             <!-- Capacity Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white bg-opacity-90 text-gray-800 shadow-sm backdrop-blur-md">
                                    <svg class="w-3 h-3 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zVW2 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Up to {{ $tent->max_capacity }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-1 p-6 flex flex-col justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition-colors">
                                    {{ $tent->name }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                                    Experience nature in comfort with our {{ $tent->name }}. Perfect for your next outdoor adventure.
                                </p>
                                
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                     <div class="flex flex-col">
                                        @if($tent->pricing_type == 'person')
                                            @php $price = $tent->prices->first(); @endphp
                                            @if($price)
                                                <span class="text-xs text-green-600 uppercase tracking-wider font-bold mb-1">Per Person / Night</span>
                                                <div class="flex items-baseline gap-4">
                                                    <div>
                                                        <span class="text-2xl font-extrabold text-gray-900">${{ number_format($price->adult_price, 0) }}</span>
                                                        <span class="text-sm font-medium text-gray-500">Adult</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-2xl font-extrabold text-gray-900">${{ number_format($price->child_price, 0) }}</span>
                                                        <span class="text-sm font-medium text-gray-500">Child</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-sm font-medium text-gray-500">Price Not Available</span>
                                            @endif
                                        @elseif($tent->pricing_type == 'base')
                                            @if($tent->prices->isNotEmpty())
                                                @php
                                                    $minPrice = $tent->prices->min('price_weekday');
                                                @endphp
                                                <span class="text-xs text-green-600 uppercase tracking-wider font-bold mb-1">Unit Price / Night</span>
                                                <div class="flex items-baseline">
                                                    <span class="text-sm text-gray-500 font-medium mr-1">from</span>
                                                    <span class="text-3xl font-extrabold text-gray-900">${{ number_format($minPrice, 0) }}</span>
                                                </div>
                                            @else
                                                 <span class="text-sm font-medium text-gray-500">Price Not Available</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex items-center justify-between gap-3">
                                <a href="/tents/{{ $tent->id }}/edit" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                    Edit
                                </a>
                                <form action="/tents/{{ $tent->id }}/delete" method="POST" class="flex-1 inline-block" onsubmit="return confirm('Are you sure you want to delete this tent? This action cannot be undone.');">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($tents->isEmpty())
                <div class="text-center py-24">
                    <div class="mx-auto h-24 w-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No tents available</h3>
                    <p class="mt-2 text-gray-500">Get started by adding your first tent to the inventory.</p>
                     <div class="mt-6">
                        <a href="/tents/create" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add New Tent
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>
