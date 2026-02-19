<x-layout>
    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Create an account</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Start managing your campsite bookings
                </p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ url('/register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Full name
                    </label>
                    <input
                        name="name"
                        type="text"
                        required
                        autofocus
                        value="{{ old('name') }}"
                        placeholder="John Doe"
                        class="mt-1 block w-full rounded-xl border border-gray-300 
                               px-4 py-3 text-sm text-gray-900 placeholder-gray-400
                               shadow-sm
                               focus:border-green-500 focus:ring-2 focus:ring-green-500
                               transition"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <input
                        name="email"
                        type="email"
                        required
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

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <input
                        name="password"
                        type="password"
                        required
                        placeholder="••••••••"
                        class="mt-1 block w-full rounded-xl border border-gray-300 
                               px-4 py-3 text-sm text-gray-900 placeholder-gray-400
                               shadow-sm
                               focus:border-green-500 focus:ring-2 focus:ring-green-500
                               transition"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Confirm password
                    </label>
                    <input
                        name="password_confirmation"
                        type="password"
                        required
                        placeholder="••••••••"
                        class="mt-1 block w-full rounded-xl border border-gray-300 
                               px-4 py-3 text-sm text-gray-900 placeholder-gray-400
                               shadow-sm
                               focus:border-green-500 focus:ring-2 focus:ring-green-500
                               transition"
                    >
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white
                           hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500
                           focus:ring-offset-2 transition"
                >
                    Create account
                </button>
            </form>

            <!-- Footer -->
            <p class="mt-6 text-center text-sm text-gray-500">
                Already have an account?
                <a href="{{ url('/login') }}" class="font-medium text-green-600 hover:text-green-700">
                    Sign in
                </a>
            </p>

        </div>
    </div>
</x-layout>
