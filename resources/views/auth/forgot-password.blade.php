<x-layout>
    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Forgot your password?</h2>
                <p class="text-sm text-gray-500 mt-1">
                    No worries — we’ll send you a reset link
                </p>
            </div>

            <!-- Status Message -->
            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ url('/forgot-password') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <input
                        name="email"
                        type="email"
                        required
                        autofocus
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        class="mt-1 block w-full rounded-xl border border-gray-300
                               px-4 py-3 text-sm text-gray-900 placeholder-gray-400
                               shadow-sm
                               focus:border-green-500 focus:ring-2 focus:ring-green-500
                               transition"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white
                           hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500
                           focus:ring-offset-2 transition"
                >
                    Email password reset link
                </button>
            </form>

            <!-- Footer -->
            <p class="mt-6 text-center text-sm text-gray-500">
                Remembered your password?
                <a href="{{ url('/login') }}" class="font-medium text-green-600 hover:text-green-700">
                    Back to login
                </a>
            </p>

        </div>
    </div>
</x-layout>
