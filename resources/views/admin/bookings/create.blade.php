<x-admin>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Create Manual Booking</h1>
                <p class="mt-2 text-lg text-gray-500">Record a new reservation for offline or phone-in customers.</p>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 shadow-sm border border-green-100">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

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

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Reservation Details Card -->
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            Reservation Details
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Select the requested dates first. Only campsites with available slots for these dates will be shown.</p>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Check-in -->
                            <div>
                                <label for="check_in_date" class="block text-sm font-bold text-gray-700">Check-in Date <span class="text-red-500">*</span></label>
                                <input type="date" name="check_in_date" id="check_in_date" value="{{ old('check_in_date', request('date')) }}" required class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                            </div>

                            <!-- Check-out -->
                            <div>
                                <label for="check_out_date" class="block text-sm font-bold text-gray-700">Check-out Date <span class="text-red-500">*</span></label>
                                <input type="date" name="check_out_date" id="check_out_date" value="{{ old('check_out_date') }}" required class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                            </div>

                            <!-- Campsite -->
                            <div class="md:col-span-2">
                                <label for="tent_id" class="block text-sm font-bold text-gray-700">Available Campsite <span class="text-red-500">*</span></label>
                                <select id="tent_id" name="tent_id" required disabled class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4 disabled:bg-gray-100 disabled:text-gray-500">
                                    <option value="" disabled selected>Select dates to see available campsites...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Info Card -->
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <div class="bg-green-100 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            Customer Information
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Provide the guest's contact info. If they don't have an email, use a placeholder (e.g. guest@local.com).</p>
                    </div>

                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="customer_name" class="block text-sm font-bold text-gray-700">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-bold text-gray-700">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" required class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                            </div>

                            <div class="md:col-span-2">
                                <label for="customer_phone" class="block text-sm font-bold text-gray-700">Phone Number <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                            </div>

                            <div class="md:col-span-2">
                                <label for="customer_address" class="block text-sm font-bold text-gray-700">Street Address <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="text" name="customer_address" id="customer_address" value="{{ old('customer_address') }}" class="mt-2 block w-full shadow-sm sm:text-sm border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 py-3 px-4">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Card -->
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <div class="bg-yellow-100 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            Financials & Status
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Set the final agreed price and the initial booking status.</p>
                    </div>

                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="total_price" class="block text-sm font-bold text-gray-700">Total Price <span class="text-red-500">*</span></label>
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-lg">RM</span>
                                    </div>
                                    <input type="number" step="0.01" name="total_price" id="total_price" value="{{ old('total_price') }}" required class="block w-full pl-12 py-3 shadow-sm sm:text-lg border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" placeholder="0.00">
                                </div>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Booking Status <span class="text-red-500">*</span></label>
                                <select id="status" name="status" class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg border">
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed (Paid)</option>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending Payment</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Bar -->
                <div class="flex items-center justify-end pb-8 space-x-4">
                    <a href="{{ route('admin.bookings.index') }}" class="bg-white py-3 px-6 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-transform transform hover:-translate-y-0.5">
                        Create Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.getElementById('check_in_date');
        const checkOutInput = document.getElementById('check_out_date');
        const tentSelect = document.getElementById('tent_id');
        const originalOldTentId = "{{ old('tent_id') }}";

        function checkAvailability() {
            const checkIn = checkInInput.value;
            const checkOut = checkOutInput.value;

            if (!checkIn || !checkOut) {
                tentSelect.innerHTML = '<option value="" disabled selected>Select dates to see available campsites...</option>';
                tentSelect.disabled = true;
                return;
            }

            if (new Date(checkIn) >= new Date(checkOut)) {
                tentSelect.innerHTML = '<option value="" disabled selected>Check-out must be after check-in</option>';
                tentSelect.disabled = true;
                return;
            }

            tentSelect.innerHTML = '<option value="" disabled selected>Checking availability...</option>';
            tentSelect.disabled = true;

            fetch(`/admin/bookings/tents?check_in_date=${checkIn}&check_out_date=${checkOut}`)
                .then(response => response.json())
                .then(tents => {
                    tentSelect.innerHTML = '';
                    if (tents.length === 0) {
                        tentSelect.innerHTML = '<option value="" disabled selected>No campsites available for these dates.</option>';
                        tentSelect.disabled = true;
                    } else {
                        tentSelect.innerHTML = '<option value="" disabled selected>Choose an available campsite...</option>';
                        tents.forEach(tent => {
                            const option = document.createElement('option');
                            option.value = tent.id;
                            option.textContent = tent.name;
                            if (tent.id == originalOldTentId) {
                                option.selected = true;
                            }
                            tentSelect.appendChild(option);
                        });
                        tentSelect.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error fetching tents:', error);
                    tentSelect.innerHTML = '<option value="" disabled selected>Error checking availability</option>';
                });
        }

        checkInInput.addEventListener('change', checkAvailability);
        checkOutInput.addEventListener('change', checkAvailability);

        // Initial check if values are pre-filled
        if (checkInInput.value && checkOutInput.value) {
            checkAvailability();
        }
    });
</script>
