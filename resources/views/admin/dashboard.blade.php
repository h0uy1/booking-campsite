<x-admin>
    <div class="space-y-8 pb-12">
        <!-- Hero Section / Header Summary -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                    Dashboard Overview
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Welcome back. Here is what's happening at CampManager today.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="/admin/tents/create" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Tent
                </a>
                <a href="/admin/dashboard" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white border border-transparent rounded-lg shadow-sm text-sm font-medium hover:bg-black transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Report
                </a>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Revenue KPI -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-1">30-Day Revenue</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">${{ number_format($stats['monthly_revenue'], 2) }}</h3>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 font-medium flex items-center bg-green-50 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Gross
                    </span>
                    <span class="text-gray-400 ml-2">from confirmed</span>
                </div>
            </div>

            <!-- Active Bookings KPI -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-8 8h8m-8 4h8m-8 4h8"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-1">Active Bookings</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['active_bookings'] }}</h3>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-blue-600 font-medium flex items-center bg-blue-50 px-2 py-0.5 rounded-full text-xs">
                        Confirmed Active
                    </span>
                </div>
            </div>

            <!-- Total Tents KPI -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-16 h-16 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-1">Tent Models</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_tents'] }}</h3>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-500 text-xs">Unique configurations</span>
                </div>
            </div>

            <!-- Total Slots KPI -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-16 h-16 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-500 mb-1">Inventory Slots</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_slots'] }}</h3>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-500 text-xs">Total physical campsites</span>
                </div>
            </div>

        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Bookings table -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Bookings Table -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900">Recent Transactions</h3>
                        <a href="/admin/bookings" class="text-sm font-medium text-green-600 hover:text-green-700">View All</a>
                    </div>
                    <div class="bg-white">
                        @if(count($stats['recent_bookings']) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($stats['recent_bookings'] as $booking)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-medium text-xs mr-3">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">#BK-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                                                            <div class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'Guest' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $booking->slot->tent->name ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                                    ${{ number_format($booking->total_price, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
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
                        @else
                            <div class="p-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions</h3>
                                <p class="mt-1 text-sm text-gray-500">No bookings have been made recently.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar content -->
            <div class="space-y-8">
                
                <!-- System Health -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                            System Health
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex flex-col gap-1">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 font-medium">Database Load</span>
                                <span class="text-gray-900 font-bold">12%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: 12%"></div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 font-medium">Storage Capacity</span>
                                <span class="text-gray-900 font-bold">45%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600 font-medium">Uptime Guarantee</span>
                                <span class="text-green-600 font-bold">99.9%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Inventory Changes -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Inventory Logs</h3>
                        <a href="/admin/tents" class="text-xs font-medium text-gray-500 hover:text-gray-900">Manage</a>
                    </div>
                    <div class="divide-y divide-gray-100 p-2">
                        @forelse($stats['recent_tents'] as $tent)
                            <a href="/admin/tents/{{ $tent->id }}/edit" class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                                <div class="h-10 w-10 flex-shrink-0 rounded-lg overflow-hidden border border-gray-200">
                                    @if($tent->image)
                                        <img src="{{ Storage::url($tent->image) }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs font-bold">N/A</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $tent->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">Capacity: {{ $tent->max_capacity }} | {{ ucfirst($tent->pricing_type) }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @empty
                            <div class="p-6 text-center text-xs text-gray-500">No inventory updates.</div>
                        @endforelse
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-admin>