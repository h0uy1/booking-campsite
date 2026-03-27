<x-admin>
    <div class="space-y-8 pb-12">
        <!-- Professional Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Inventory Management</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your campsite models, pricing units, and physical inventory.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="/admin/tents/create" class="inline-flex items-center px-4 py-2 bg-green-600 text-white border border-transparent rounded-lg shadow-sm text-sm font-medium hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add New Model
                </a>
            </div>
        </div>

        <!-- Inventory Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm transition-all" :class="darkMode ? 'dark mb-0' : ''">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Models</p>
                <h3 class="text-3xl font-bold">{{ $tents->count() }}</h3>
                <div class="mt-2 text-xs text-gray-400">Unique campsite configurations</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm transition-all" :class="darkMode ? 'dark mb-0' : ''">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Capacity</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ $tents->sum('max_capacity') }}</h3>
                <div class="mt-2 text-xs text-gray-400">Total guest capacity across all models</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm transition-all" :class="darkMode ? 'dark mb-0' : ''">
                <p class="text-sm font-medium text-gray-500 mb-1">Inventory Status</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <span class="text-sm font-bold">Active</span>
                </div>
                <div class="mt-2 text-xs text-gray-400">All models are currently listed</div>
            </div>
        </div>

        <!-- Professional Inventory List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" :class="darkMode ? 'dark' : ''">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-gray-50/50 gap-4" :class="darkMode ? 'bg-gray-900 border-gray-800' : ''">
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" placeholder="Filter models..." class="block w-full rounded-lg border-gray-300 py-1.5 pl-9 text-sm focus:ring-green-500 focus:border-green-500 bg-white" :class="darkMode ? 'bg-gray-800 border-gray-700 text-white' : ''">
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest">
                    {{ $tents->count() }} Entries found
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" :class="darkMode ? 'divide-gray-800' : ''">
                    <thead class="bg-gray-50" :class="darkMode ? 'bg-gray-900' : ''">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Campsite Model</th>
                            <th scope="col" class="px-6 py-3 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Pricing Structure</th>
                            <th scope="col" class="px-6 py-3 text-left text-[10px] font-black text-gray-500 uppercase tracking-widest">Specs</th>
                            <th scope="col" class="px-6 py-3 text-right text-[10px] font-black text-gray-500 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" :class="darkMode ? 'divide-gray-800 bg-gray-950' : 'bg-white'">
                        @forelse($tents as $tent)
                            <tr class="hover:bg-gray-50/50 transition-colors" :class="darkMode ? 'hover:bg-gray-900/50' : ''">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="h-24 w-32 d:h-20 md:w-28 overflow-hidden rounded-xl border border-gray-100 flex-shrink-0 bg-gray-100" :class="darkMode ? 'border-gray-800 bg-gray-800' : ''">
                                            @if($tent->image)
                                                <img class="h-full w-full object-cover transition-transform duration-500 hover:scale-110" src="{{ asset('storage/' . $tent->image) }}" alt="">
                                            @elseif($tent->images->isNotEmpty())
                                                <img class="h-full w-full object-cover transition-transform duration-500 hover:scale-110" src="{{ asset('storage/' . $tent->images->first()->image_path) }}" alt="">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-black text-gray-900 truncate tracking-tight">{{ $tent->name }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">ID: #{{ str_pad($tent->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        @if($tent->pricing_type == 'person')
                                            @php $price = $tent->prices->first(); @endphp
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <span class="px-1.5 py-0.5 rounded bg-blue-50 text-[9px] font-black text-blue-600 uppercase">Per Person</span>
                                            </div>
                                            @if($price)
                                                <div class="flex items-center gap-3 text-xs">
                                                    <span class="font-bold text-gray-900">RM{{ number_format($price->adult_price, 0) }} <span class="text-[10px] text-gray-400 font-normal">Adult</span></span>
                                                    <span class="font-bold text-gray-900">RM{{ number_format($price->child_price, 0) }} <span class="text-[10px] text-gray-400 font-normal">Child</span></span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <span class="px-1.5 py-0.5 rounded bg-green-50 text-[9px] font-black text-green-600 uppercase tracking-widest">Base Rate</span>
                                            </div>
                                            @php $minPrice = $tent->prices->min('price_weekday'); @endphp
                                            <div class="text-sm font-black text-gray-900">
                                                RM{{ number_format($minPrice ?? 0, 0) }} <span class="text-[10px] text-gray-400 font-normal tracking-normal uppercase">Starting</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                            <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zVW2 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            <span class="font-bold">{{ $tent->max_capacity }} <span class="text-[10px] font-normal text-gray-400">Max Capacity</span></span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-[10px] text-gray-400 uppercase font-bold tracking-wider">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $tent->images->count() }} Media Assets
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2 text-xs font-bold uppercase tracking-widest">
                                        <a href="/admin/tents/{{ $tent->id }}/edit" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Edit Model">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="/admin/tents/{{ $tent->id }}/delete" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this tent? This action cannot be undone.');">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete Model">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-24 text-center">
                                    <div class="mx-auto h-16 w-16 rounded-full bg-gray-50 flex items-center justify-center mb-4" :class="darkMode ? 'bg-gray-800' : ''">
                                        <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">No models found</h3>
                                    <p class="mt-1 text-xs text-gray-500">Add your first campsite model to get started with inventory.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin>
