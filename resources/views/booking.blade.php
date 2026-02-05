

<x-layout>
      <!-- Breadcrumbs & Header -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-gray-700">Dashboard</a></li>
                    <li>/</li>
                    <li><a href="#" class="hover:text-gray-700">Bookings</a></li>
                    <li>/</li>
                    <li class="font-medium text-gray-900">#BK-782910</li>
                </ol>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Booking #BK-782910</h1>
                    <p class="mt-1 text-sm text-gray-500">Created on Oct 14, 2023 at 10:23 AM</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Status Badge -->
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Confirmed
                    </span>
                    
                    <button class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print
                    </button>
                    
                    <button class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                        Check In Guest
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Main Information (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card: Campsite Details -->
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Campsite Information</h3>
                        <span class="text-sm text-gray-500">Zone A - Forest Edge</span>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Image Placeholder -->
                            <div class="w-full md:w-1/3 h-40 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 relative overflow-hidden group">
                                <img src="https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Campsite" class="absolute inset-0 w-full h-full object-cover">
                            </div>

                            <div class="flex-1 space-y-4">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Riverside Spot #14</h2>
                                    <p class="text-sm text-gray-500 mt-1">Premium Tent Site (No RV Hookups)</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <p class="text-xs font-semibold text-blue-600 uppercase">Check-in</p>
                                        <p class="text-lg font-bold text-gray-900">Nov 12, 2023</p>
                                        <p class="text-xs text-gray-500">After 2:00 PM</p>
                                    </div>
                                    <div class="bg-blue-50 p-3 rounded-lg">
                                        <p class="text-xs font-semibold text-blue-600 uppercase">Check-out</p>
                                        <p class="text-lg font-bold text-gray-900">Nov 15, 2023</p>
                                        <p class="text-xs text-gray-500">Before 11:00 AM</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Duration: <span class="font-semibold ml-1 text-gray-900">3 Nights</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Guest Details -->
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Guest Details</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center md:text-left">
                            <div class="flex items-center p-4 border border-gray-100 rounded-lg bg-gray-50">
                                <div class="p-3 bg-white rounded-full shadow-sm mr-4">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Adults</p>
                                    <p class="text-xl font-bold text-gray-900">2</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 border border-gray-100 rounded-lg bg-gray-50">
                                <div class="p-3 bg-white rounded-full shadow-sm mr-4">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Children</p>
                                    <p class="text-xl font-bold text-gray-900">2</p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 border border-gray-100 rounded-lg bg-gray-50">
                                <div class="p-3 bg-white rounded-full shadow-sm mr-4">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Pets</p>
                                    <p class="text-xl font-bold text-gray-900">1 (Dog)</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Special Requests</h4>
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            Guest requested a spot near the hiking trail entrance if possible.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Vehicle Info -->
                 <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                         <h3 class="text-lg font-medium text-gray-900">Registration Details</h3>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div>
                            <span class="block text-sm text-gray-500">Vehicle Plate</span>
                            <span class="block font-medium text-gray-900">WXY 1234</span>
                         </div>
                         <div>
                            <span class="block text-sm text-gray-500">Vehicle Type</span>
                            <span class="block font-medium text-gray-900">SUV (Honda CRV)</span>
                         </div>
                    </div>
                 </div>

            </div>

            <!-- Right Column: Sidebar (1/3 width) -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Card: Customer Info -->
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Customer</h3>
                        <a href="#" class="text-sm text-green-600 hover:text-green-800 font-medium">View Profile</a>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <img class="h-12 w-12 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Alex+Morgan&background=random" alt="Avatar">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Alex Morgan</p>
                                <p class="text-xs text-gray-500">Member since 2021</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span class="text-sm text-gray-600 truncate">alex.morgan@example.com</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <span class="text-sm text-gray-600">+1 (555) 019-2834</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-sm text-gray-600">San Francisco, CA</span>
                            </div>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-center">
                            <button class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                Send Email
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card: Payment Summary -->
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Payment Summary</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">3 Nights x $45.00</span>
                            <span class="text-gray-900 font-medium">$135.00</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Pet Fee</span>
                            <span class="text-gray-900 font-medium">$15.00</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Service Fee</span>
                            <span class="text-gray-900 font-medium">$10.00</span>
                        </div>
                        <div class="flex justify-between mb-2 text-green-600">
                            <span class="text-sm">Discount (Member)</span>
                            <span class="font-medium">-$10.00</span>
                        </div>
                        
                        <div class="border-t border-gray-200 my-4 pt-4 flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-green-700">$150.00</span>
                        </div>

                        <div class="bg-green-50 rounded-md p-3 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-sm font-medium text-green-800">Paid in Full</span>
                            </div>
                            <span class="text-xs text-green-600">via Credit Card **** 4242</span>
                        </div>
                    </div>
                </div>

                <!-- Card: Actions -->
                <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-4 space-y-3">
                        <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Booking
                        </button>
                        <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-200 shadow-sm text-sm font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-100">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Cancel Booking
                        </button>
                    </div>
                </div>

            </div>
        </div>
</x-layout>