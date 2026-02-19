<x-layout>
    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3v1h-6v-1zm0 4h6v5H6v-5h6zm-3-4c0-3.314 2.686-6 6-6s6 2.686 6 6v1h1a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2h1v-1z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Reset password</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Choose a new password for your account
                </p>
            </div>

            <!-- Reset Password Form -->
            <form method="POST" action="{{ url('/reset-password') }}" class="space-y-5">
                @csrf

                <!-- Token -->
                <input type="hidden" name="token" value="{{ request()->route('token') }}">

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <input
                        name="email"
                        type="email"
                        required
                        value="{{ old('email', request('email')) }}"
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

                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        New password
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
                        Confirm new password
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
                    Reset password
                </button>
            </form>

            <!-- Footer -->
            <p class="mt-6 text-center text-sm text-gray-500">
                Changed your mind?
                <a href="{{ url('/login') }}" class="font-medium text-green-600 hover:text-green-700">
                    Back to login
                </a>
            </p>

        </div>
    </div>
</x-layout>
