<x-layout>
    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Sign in to manage your campsite bookings
                </p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <input name="email" type="email" required autofocus value="{{ old('email') }}"
                        placeholder="you@example.com"
                        class="mt-1 block w-full rounded-xl border border-gray-200 
           bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400
           shadow-sm
           focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:ring-offset-0
           transition" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <input name="password" type="password" required placeholder="••••••••"
                        class="mt-1 block w-full rounded-xl border border-gray-200 
       bg-gray-50 px-4 py-3 text-sm
       focus:bg-white focus:border-green-500 focus:ring-2 focus:ring-green-500
       transition" />

                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember"
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2">Remember me</span>
                    </label>

                    <a href="{{ url('/forgot-password') }}"
                        class="text-sm font-medium text-green-600 hover:text-green-700">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                    Sign in
                </button>
            </form>

            <!-- Footer -->
            <p class="mt-6 text-center text-sm text-gray-500">
                Don’t have an account?
                <a href="{{ url('/register') }}" class="font-medium text-green-600 hover:text-green-700">
                    Create one
                </a>
            </p>
        </div>
    </div>
</x-layout>
