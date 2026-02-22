<x-admin>
    <div class="space-y-8 pb-12">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Reservations</h1>
                <p class="text-sm text-gray-500 mt-1">Manage and track all campsite bookings.</p>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Bookings</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total']) }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm border-l-4 border-l-yellow-400">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['pending']) }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm border-l-4 border-l-green-400">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Confirmed</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['confirmed']) }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden bg-gradient-to-br from-green-600 to-green-700">
                <p class="text-xs font-bold text-green-100 uppercase tracking-wider">30-Day Revenue</p>
                <h3 class="text-2xl font-bold text-white mt-1">${{ number_format($stats['revenue_30d'], 2) }}</h3>
                <div class="absolute -right-2 -bottom-2 opacity-10">
                    <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID or guest..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm">
                </div>

                <select name="status" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-xl shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <input type="date" name="date" value="{{ request('date') }}" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-xl shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-green-700 transition-colors">Apply</button>
                    <a href="{{ route('admin.bookings.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-200 transition-colors text-center">Clear</a>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Guest</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Tent / Slot</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Check-In / Out</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">#BK-{{ sprintf('%05d', $booking->id) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-xs">
                                            {{ strtoupper(substr($booking->user->name ?? 'G', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-bold text-gray-900">{{ $booking->user->name ?? 'Guest User' }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ $booking->slot->tent->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">Slot: {{ $booking->slot->tent_number ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">to {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-900">${{ number_format($booking->total_price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'confirmed' => 'bg-green-100 text-green-800 border-green-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button @click="open = !open" type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20 focus:outline-none" style="display: none;">
                                            <div class="py-1">
                                                <form action="{{ route('admin.bookings.status', $booking->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($booking->status !== 'confirmed')
                                                        <button type="submit" name="status" value="confirmed" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 font-medium">Mark Confirmed</button>
                                                    @endif
                                                    @if($booking->status !== 'pending')
                                                        <button type="submit" name="status" value="pending" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 font-medium">Mark Pending</button>
                                                    @endif
                                                    @if($booking->status !== 'cancelled')
                                                        <button type="submit" name="status" value="cancelled" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">Cancel Booking</button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 italic">No reservations found matching your criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin>
