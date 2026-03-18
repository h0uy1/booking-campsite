<x-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 text-center">
                <!-- Cancel / Warning Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Payment Cancelled</h2>
                
                <p class="text-md text-gray-600 mb-8">
                    Your booking process was interrupted and your payment wasn't completed. Your booking request has been <span class="font-bold text-red-600">cancelled</span>.
                </p>

                <div class="space-y-4">
                    <a href="{{ route('booking.index') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        Try Booking Again
                    </a>
                    
                    <a href="{{ route('user.bookings') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        Go to My Bookings
                    </a>
                </div>
            </div>
            
            <p class="mt-8 text-center text-sm text-gray-500">
                Having trouble paying? <a href="#" class="font-medium text-green-600 hover:text-green-500">Contact Support</a>
            </p>
        </div>
    </div>
</x-layout>
