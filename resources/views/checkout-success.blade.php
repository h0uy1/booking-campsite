<x-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Payment Received!</h2>
                
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8 text-left">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Reservation Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Campsite:</span>
                            <span class="font-medium text-gray-900">{{ $booking->slot->tent->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dates:</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-gray-600 font-semibold">Total Paid:</span>
                            <span class="font-bold text-green-600">RM{{ number_format($booking->total_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-white p-3 rounded-md border border-gray-100 shadow-sm mt-4">
                            <span class="text-gray-600">Status:</span>
                            @if($booking->status === 'confirmed')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Confirmed</span>
                            @else
                                <div class="flex items-center text-xs font-semibold text-yellow-800">
                                    <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-yellow-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Processing Payment...
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-8">
                    @if($booking->status === 'confirmed')
                        Your booking is now <span class="font-bold text-green-600">confirmed</span>. We have sent you an email with the reservation details.
                    @else
                        We are currently confirming your payment. This page will update automatically when your booking is <span class="font-bold text-green-600">confirmed</span>.
                    @endif
                </p>

                @if($booking->status !== 'confirmed')
                <script>
                    // Poll for status update every 5 seconds until confirmed
                    setInterval(function() {
                        location.reload();
                    }, 5000);
                </script>
                @endif

                <div class="space-y-4">
                    <a href="{{ route('user.bookings') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        View My Bookings
                    </a>
                    
                    <a href="{{ route('booking.index') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        Back to Home
                    </a>
                </div>
            </div>
            
            <p class="mt-8 text-center text-sm text-gray-500">
                Need help? <a href="#" class="font-medium text-green-600 hover:text-green-500">Contact Support</a>
            </p>
        </div>
    </div>
</x-layout>
