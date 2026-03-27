<x-admin>
    <div class="space-y-8 pb-12">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Blockout Dates</h1>
                <p class="mt-2 text-sm text-gray-500">Manage fully closed dates for the campsite</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.blockouts.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Blockout
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4 shadow-sm border border-green-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Date Range</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Reason</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($blockouts as $blockout)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">
                                        {{ $blockout->start_date->format('M d, Y') }} - {{ $blockout->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $blockout->start_date->diffInDays($blockout->end_date) + 1 }} Days
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $blockout->reason ?: 'No reason provided' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $isPast = $blockout->end_date->isPast();
                                        $isActive = $blockout->start_date->isPast() && !$isPast;
                                        $isFuture = $blockout->start_date->isFuture();
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border 
                                        {{ $isPast ? 'bg-gray-100 text-gray-800 border-gray-200' : '' }}
                                        {{ $isActive ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                                        {{ $isFuture ? 'bg-blue-100 text-blue-800 border-blue-200' : '' }}">
                                        {{ $isPast ? 'Completed' : ($isActive ? 'Currently Active' : 'Upcoming') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.blockouts.destroy', $blockout->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this blockout and reopen the dates?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Unblock</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">No blockout dates configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($blockouts->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $blockouts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin>
