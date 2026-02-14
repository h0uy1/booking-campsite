<x-layout>
    <div class="space-y-10 pb-12">
        <!-- Hero Section -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-green-600 to-blue-700 p-8 md:p-12 shadow-2xl">
            <div class="relative z-10 max-w-2xl">
                <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                    Welcome back, <span class="text-green-200 text-opacity-90 italic">Admin</span>
                </h1>
                <p class="text-green-50 text-opacity-80 text-lg md:text-xl leading-relaxed font-medium">
                    Manage your campsite inventory, track availability, and handle bookings with ease. Your property snapshot is ready.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="/tents/create" class="inline-flex items-center px-6 py-3 bg-white text-green-700 font-bold rounded-xl shadow-lg hover:bg-green-50 transition-all transform hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Add New Tent
                    </a>
                    <a href="/tents" class="inline-flex items-center px-6 py-3 bg-green-500 bg-opacity-20 backdrop-blur-md text-white border border-white border-opacity-30 font-bold rounded-xl hover:bg-opacity-30 transition-all transform hover:-translate-y-1">
                        Manage Inventory
                    </a>
                </div>
            </div>
            
            <!-- Decorative circle -->
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-white bg-opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 w-64 h-64 bg-green-400 bg-opacity-10 rounded-full blur-3xl"></div>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Statistics Column -->
            <div class="lg:col-span-2 space-y-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Stat Card: Tents -->
                    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:shadow-2xl transition-all group overflow-hidden relative">
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Total Tent Types</p>
                                <h3 class="text-4xl font-black text-gray-900">{{ $stats['total_tents'] }}</h3>
                            </div>
                            <div class="bg-green-100 text-green-600 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs font-medium text-gray-500">
                            <span class="text-green-500 font-bold mr-1">Active</span> status for all listings
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-green-50 rounded-full opacity-50"></div>
                    </div>

                    <!-- Stat Card: Slots -->
                    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:shadow-2xl transition-all group overflow-hidden relative text-blue-900">
                        <div class="relative z-10 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Total Available Slots</p>
                                <h3 class="text-4xl font-black text-gray-900">{{ $stats['total_slots'] }}</h3>
                            </div>
                            <div class="bg-blue-100 text-blue-600 p-4 rounded-2xl group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-xs font-medium text-gray-500">
                            Individual units across all clusters
                        </div>
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full opacity-50"></div>
                    </div>
                </div>

                <!-- Recent Listings -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-xl font-black text-gray-900">Recently Added</h3>
                        <a href="/tents" class="text-green-600 font-bold text-sm hover:underline italic">View all inventory &rarr;</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($stats['recent_tents'] as $tent)
                        <div class="px-8 py-6 hover:bg-gray-50 transition-colors flex items-center gap-6 group">
                            <div class="h-20 w-20 flex-shrink-0 relative">
                                @if($tent->image)
                                    <img src="{{ Storage::url($tent->image) }}" class="h-full w-full object-cover rounded-2xl shadow-md group-hover:shadow-lg transition-shadow" alt="">
                                @else
                                    <div class="h-full w-full bg-green-50 rounded-2xl flex items-center justify-center text-green-200 font-bold">No Image</div>
                                @endif
                                <div class="absolute -top-2 -right-2 bg-white rounded-full p-1 shadow-sm border border-gray-100">
                                    <span class="flex h-3 w-3 rounded-full bg-green-500 animate-pulse"></span>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <h4 class="text-lg font-bold text-gray-900 capitalize">{{ $tent->name }}</h4>
                                <div class="flex items-center gap-4 mt-1">
                                    <span class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        {{ $tent->max_capacity }} Max
                                    </span>
                                    <span class="flex items-center text-sm text-gray-500 italic">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        {{ ucfirst($tent->pricing_type) }} Pricing
                                    </span>
                                </div>
                            </div>
                            <a href="/tents/{{ $tent->id }}/edit" class="bg-gray-100 hover:bg-green-600 hover:text-white p-3 rounded-xl transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                        </div>
                        @empty
                        <div class="px-8 py-16 text-center">
                            <div class="bg-gray-50 h-20 w-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-gray-400 font-medium">No inventory yet. Start by adding your first tent.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Bookings Table -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-xl font-black text-gray-900">Recent Bookings</h3>
                        <a href="/user" class="text-purple-600 font-bold text-sm hover:underline italic">View all bookings &rarr;</a>
                    </div>
                    <div class="bg-white">
                        @if(count($stats['recent_bookings']) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tent / Slot</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($stats['recent_bookings'] as $booking)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    #{{ $booking->id }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-900">{{ $booking->slot->tent->name ?? 'Unknown Tent' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $booking->slot->tent_number ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-xs text-gray-900">
                                                        <span class="font-bold text-green-600">In:</span> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d') }}
                                                    </div>
                                                    <div class="text-xs text-gray-900">
                                                        <span class="font-bold text-red-600">Out:</span> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                           ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-6 py-3 bg-gray-50 text-right border-t border-gray-100">
                                <a href="/user" class="text-sm font-medium text-purple-600 hover:text-purple-900">View all bookings &rarr;</a>
                            </div>
                        @else
                            <div class="p-8 text-center text-gray-500 text-sm">
                                No bookings found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Bar -->
            <div class="space-y-8">
                <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 sticky top-24">
                    <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                        <span class="w-2 h-8 bg-green-500 rounded-full mr-3"></span>
                        Quick Actions
                    </h3>
                    <div class="space-y-4">
                        <a href="/tents/create" class="flex items-center w-full p-4 bg-green-50 text-green-700 rounded-2xl hover:bg-green-100 transition-all group">
                            <div class="bg-white p-2 rounded-xl shadow-sm mr-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <span class="font-black text-lg">New Tent</span>
                        </a>
                        
                        <a href="/tents" class="flex items-center w-full p-4 bg-blue-50 text-blue-700 rounded-2xl hover:bg-blue-100 transition-all group">
                            <div class="bg-white p-2 rounded-xl shadow-sm mr-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            </div>
                            <span class="font-black text-lg">Manage All</span>
                        </a>

                    </div>


                    <!-- System Health -->
                    <div class="mt-10 pt-8 border-t border-gray-100">
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">System Status</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600 italic">Database</span>
                                    <span class="flex h-2 w-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600 italic">Storage</span>
                                    <span class="flex h-2 w-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-600 italic">API Connectivity</span>
                                    <span class="flex h-2 w-2 rounded-full bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)] animate-pulse"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>