<x-layout>
    <div class="max-w-4xl mx-auto py-8">
        <!-- Page Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Booking Details 
                    <span class="text-green-600">#BK-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                </h1>
                <p class="mt-2 text-sm text-gray-700">Detailed information about your reservation.</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('user.bookings') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to All Bookings
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
            <!-- Status and Title Header -->
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200 bg-gray-50">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $booking->slot->tent->name ?? 'Campsite' }}
                    </h3>
                </div>
                <div>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            <!-- Content Details -->
            <div class="px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    
                    <!-- Dates -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Dates
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="font-medium text-gray-900">Check-in:</span> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('l, F j, Y') }}<br>
                            <span class="font-medium text-gray-900">Check-out:</span> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('l, F j, Y') }}
                        </dd>
                    </div>

                    <!-- Payment Details -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Total Price
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-medium">
                            @if($booking->total_price)
                                RM{{ number_format($booking->total_price, 2) }}
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>

                    <!-- Booking Created -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Booked On
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $booking->created_at->format('M d, Y, h:i A') }}
                        </dd>
                    </div>

                    <!-- Guest Details Header -->
                    <div class="bg-white px-4 py-5 border-t border-gray-200 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Guest Information
                        </h3>
                    </div>

                    <!-- Guest Details -->
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $booking->customer_name ?? auth()->user()->name }}
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <a href="mailto:{{ $booking->customer_email ?? auth()->user()->email }}" class="text-green-600 hover:text-green-500">
                                {{ $booking->customer_email ?? auth()->user()->email }}
                            </a>
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $booking->customer_phone ?? 'N/A' }}
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $booking->customer_address ?? 'N/A' }}
                        </dd>
                    </div>
                </dl>
            </div>
            
        </div>
    </div>
</x-layout>
