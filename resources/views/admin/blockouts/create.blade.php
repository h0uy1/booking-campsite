<x-admin>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Add Blockout Dates</h1>
                <p class="mt-2 text-lg text-gray-500">Close the entire campsite for specific dates to prevent any new bookings.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 24 24">
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

            <form action="{{ route('admin.blockouts.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <div class="bg-red-100 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            Closure Details
                        </h3>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-bold text-gray-700">Start Date <span class="text-red-500">*</span></label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-bold text-gray-700">End Date <span class="text-red-500">*</span></label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                            </div>

                            <!-- Reason -->
                            <div class="md:col-span-2">
                                <label for="reason" class="block text-sm font-bold text-gray-700">Reason <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" name="reason" id="reason" placeholder="e.g. Private Company Retreat" value="{{ old('reason') }}" class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Bar -->
                <div class="flex items-center justify-end pb-8 space-x-4">
                    <a href="{{ route('admin.blockouts.index') }}" class="bg-white py-3 px-6 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-transform transform hover:-translate-y-0.5">
                        Close Campsite
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin>
