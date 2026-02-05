<x-layout>        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Create New Booking</h1>
            <p class="mt-1 text-sm text-gray-500">Fill in the details to reserve a spot. Prices are calculated automatically based on the zone.</p>
        </div>

        <form action="#" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- LEFT COLUMN: Form Inputs -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Section 1: Guest Information -->
                <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Guest Information
                        </h3>
                        <button type="button" class="text-sm text-green-600 hover:text-green-800 font-medium">
                            + New Guest
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Search Existing Guest -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search Existing Guest</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 border" placeholder="Search by name, email or phone...">
                            </div>
                        </div>

                        <div class="relative flex py-2 items-center">
                            <div class="flex-grow border-t border-gray-200"></div>
                            <span class="flex-shrink-0 mx-4 text-gray-400 text-xs">OR ENTER DETAILS MANUALLY</span>
                            <div class="flex-grow border-t border-gray-200"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                                <input type="text" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Dates & Campsite -->
                <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Dates & Spot Selection
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Check-in Date <span class="text-red-500">*</span></label>
                                <input type="date" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Check-out Date <span class="text-red-500">*</span></label>
                                <input type="date" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                        </div>

                        <!-- Zone & Spot -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Zone</label>
                                <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    <option>Zone A - Riverside</option>
                                    <option>Zone B - Forest Edge</option>
                                    <option>Zone C - Mountain View (RV Only)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Specific Spot</label>
                                <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    <option disabled selected>Select dates first...</option>
                                    <option>A-01 (Available)</option>
                                    <option>A-02 (Available)</option>
                                    <option disabled class="text-gray-400 bg-gray-50">A-03 (Occupied)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Occupancy & Details -->
                <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Occupancy & Vehicle
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Adults</label>
                                <input type="number" value="2" min="1" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Children</label>
                                <input type="number" value="0" min="0" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pets ($10/pet)</label>
                                <input type="number" value="0" min="0" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                             <div>
                                <label class="block text-sm font-medium text-gray-700">Vehicle Plate Number</label>
                                <input type="text" placeholder="e.g. WXY 1234" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vehicle Type</label>
                                <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                                    <option>Sedan / SUV</option>
                                    <option>RV / Motorhome</option>
                                    <option>Motorcycle</option>
                                    <option>Trailer</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Special Requests / Notes</label>
                            <textarea rows="3" class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3" placeholder="Late check-in, near bathrooms, etc..."></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Sticky Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg border border-gray-200 sticky top-24">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">Booking Summary</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        
                        <!-- Dates Preview -->
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Duration</span>
                            <span class="font-medium text-gray-900">3 Nights</span>
                        </div>

                        <!-- Line Items -->
                        <div class="space-y-2 border-t border-gray-100 pt-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Site Fee ($50 x 3)</span>
                                <span class="font-medium text-gray-900">$150.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Guest Fee (Extra)</span>
                                <span class="font-medium text-gray-900">$0.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pet Fee</span>
                                <span class="font-medium text-gray-900">$0.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Service Tax (6%)</span>
                                <span class="font-medium text-gray-900">$9.00</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="border-t border-gray-200 pt-4 pb-2">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-bold text-gray-900">Total Due</span>
                                <span class="text-2xl font-bold text-green-700">$159.00</span>
                            </div>
                        </div>

                        <!-- Payment Status Toggle -->
                        <div class="bg-gray-50 p-3 rounded-md border border-gray-200">
                             <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600 rounded border-gray-300 focus:ring-green-500">
                                <span class="text-sm font-medium text-gray-700">Mark as Paid</span>
                            </label>
                            <div class="mt-2 text-xs text-gray-500 ml-8">
                                Check this if the customer is paying immediately via Cash or POS terminal.
                            </div>
                        </div>

                        <!-- Checkbox for Email -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="send_email" name="send_email" type="checkbox" checked class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="send_email" class="font-medium text-gray-700">Send confirmation email</label>
                                <p class="text-gray-500">Guest will receive receipt and QR code.</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 space-y-3">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Create Booking
                            </button>
                            <button type="button" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                Save as Draft
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </form>

</x-layout>