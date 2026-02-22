<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CampManager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-gray-100 p-10 relative overflow-hidden">
        
        <!-- Decorative background elements -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-green-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-green-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>

        <div class="relative z-10">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="flex justify-center mb-5">
                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white shadow-lg shadow-green-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>

                <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                    CampManager
                </h2>
                <p class="text-xs font-bold uppercase tracking-widest text-green-600 mt-2">
                    Administration Portal
                </p>
            </div>

            <!-- Admin Login Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-black uppercase text-gray-500 tracking-wider mb-2">
                        Admin Email
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        autofocus
                        value="{{ old('email') }}"
                        class="block w-full rounded-2xl border-gray-200 px-4 py-4 bg-gray-50
                               focus:border-green-600 focus:ring-green-600 focus:bg-white text-sm font-medium transition-all shadow-sm"
                        placeholder="admin@campexplore.com"
                    >
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-black uppercase text-gray-500 tracking-wider mb-2">
                        Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="block w-full rounded-2xl border-gray-200 px-4 py-4 bg-gray-50
                               focus:border-green-600 focus:ring-green-600 focus:bg-white text-sm font-medium transition-all shadow-sm"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center group cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="rounded-md border-gray-300 w-4 h-4 text-green-600 focus:ring-green-500 cursor-pointer"
                        >
                        <span class="ml-2 text-sm font-bold text-gray-500 group-hover:text-gray-700 transition-colors">Remember my device</span>
                    </label>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full flex justify-center items-center gap-2 rounded-2xl bg-gray-900
                           px-4 py-4 text-sm font-black text-white shadow-xl shadow-gray-200
                           hover:bg-black hover:-translate-y-0.5
                           focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all duration-300"
                >
                    Authenticate
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                <a href="{{ route('booking.index') }}" class="text-xs font-bold text-gray-400 hover:text-green-600 transition-colors flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Return to public site
                </a>
            </div>

        </div>
    </div>

</body>
</html>
