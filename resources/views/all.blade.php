

<x-layout>
      <!-- Page Header -->
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-light tracking-tight text-stone-900">All Bookings</h1>
                <p class="mt-2 text-sm font-light tracking-wide text-stone-500">A list of all reservations including guest details, status, and payments.</p>
            </div>
            <div class="mt-4 sm:mt-0 flex gap-3">
                <a href="/user">
                    <button class="inline-flex items-center px-5 py-2.5 rounded-xl shadow-sm text-sm font-medium tracking-wide text-white bg-stone-900 hover:bg-stone-800 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Booking
                </button>
                </a>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white p-6 rounded-2xl shadow-sm mb-8 border border-stone-200">
            <form method="GET" action="/all" class="flex flex-col lg:flex-row lg:items-center gap-4">
                <!-- Search Input -->
                <div class="relative flex-[2]">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-11 pr-4 py-2.5 border border-stone-200 rounded-xl leading-5 bg-stone-50 text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-1 focus:ring-stone-900 focus:border-stone-900 focus:bg-white sm:text-sm transition-colors" placeholder="Search by ID, guest, or campsite...">
                </div>

                <!-- Status Filter -->
                <div class="flex-1">
                    <select name="status" class="block w-full pl-4 pr-10 py-2.5 border border-stone-200 bg-stone-50 text-stone-700 focus:outline-none focus:ring-1 focus:ring-stone-900 focus:border-stone-900 focus:bg-white sm:text-sm rounded-xl transition-colors appearance-none">
                        <option value="">All Statuses</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Payment</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="flex-1">
                    <input type="date" name="date" value="{{ request('date') }}" class="block w-full px-4 py-2.5 border border-stone-200 bg-stone-50 text-stone-700 focus:outline-none focus:ring-1 focus:ring-stone-900 focus:border-stone-900 focus:bg-white sm:text-sm rounded-xl transition-colors">
                </div>

                 <!-- Filter Buttons -->
                 <div class="flex flex-shrink-0 gap-3">
                    @if(request()->hasAny(['search', 'status', 'date']) && request()->query('search') != '' || request()->query('status') != '' || request()->query('date') != '')
                        <a href="/all" class="inline-flex justify-center items-center px-5 py-2.5 border border-stone-200 rounded-xl text-sm font-medium tracking-wide text-stone-600 bg-white hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-200 transition-colors w-full lg:w-auto">
                            Clear
                        </a>
                    @endif
                    <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium tracking-wide text-white bg-stone-900 hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-stone-900 transition-colors w-full lg:w-auto">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="bg-white shadow-sm overflow-hidden rounded-2xl border border-stone-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-100">
                    <thead class="bg-stone-50 border-b border-stone-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-semibold text-stone-400 uppercase tracking-widest">Booking ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-semibold text-stone-400 uppercase tracking-widest">Guest</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-semibold text-stone-400 uppercase tracking-widest">Campsite</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-semibold text-stone-400 uppercase tracking-widest">Dates</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-semibold text-stone-400 uppercase tracking-widest">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-semibold text-stone-400 uppercase tracking-widest">Total</th>
                            <th scope="col" class="relative px-6 py-4">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-stone-100">
                        
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium tracking-wide text-stone-900">
                                <a href="{{ route('user.booking.show', $booking->id) }}" class="hover:underline">#BK-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</a>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <span class="h-10 w-10 rounded-full border border-stone-200 bg-stone-50 flex items-center justify-center text-stone-600 font-semibold tracking-wider text-xs">{{ strtoupper(substr(auth()->user()->name ?? 'GU', 0, 2)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-stone-900">{{ auth()->user()->name ?? 'Guest' }}</div>
                                        <div class="text-xs font-light tracking-wide text-stone-500">{{ auth()->user()->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm font-medium text-stone-800 tracking-wide">{{ $booking->slot->tent->name ?? 'Tent Unknown' }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-xs text-stone-600 font-light tracking-wide space-y-1">
                                <div><span class="font-medium text-stone-800">In:</span> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</div>
                                <div><span class="font-medium text-stone-800">Out:</span> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-[10px] uppercase tracking-widest font-semibold rounded-md border 
                                    {{ $booking->status === 'confirmed' ? 'bg-emerald-50 text-stone-700 border-emerald-200' : 
                                      ($booking->status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-stone-100 text-stone-600 border-stone-200') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium tracking-wide text-stone-900">
                                @if($booking->total_price)
                                    RM{{ number_format($booking->total_price, 2) }}
                                @else
                                    <span class="text-stone-400 font-light italic">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('user.booking.show', $booking->id) }}" class="text-stone-400 hover:text-stone-900 transition-colors inline-block p-1 hover:bg-stone-50 border border-transparent hover:border-stone-200 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-stone-500">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-stone-50 border border-stone-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="h-8 w-8 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    </div>
                                    <h3 class="text-base font-medium tracking-wide text-stone-900">No bookings found</h3>
                                    <p class="mt-2 text-sm font-light text-stone-500 max-w-sm">You haven't made any reservations yet. Find a beautiful campsite to start your journey!</p>
                                    <div class="mt-8">
                                        <a href="/user" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium tracking-wide rounded-xl text-white bg-stone-900 hover:bg-stone-800 transition-colors">
                                            Find a Campsite
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
</x-layout>
