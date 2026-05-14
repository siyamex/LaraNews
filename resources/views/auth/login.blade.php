<x-guest-layout>
    <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 p-8">

        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Welcome back</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sign in to your account</p>
        </div>

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl">
            @foreach($errors->all() as $error)
            <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        @session('status')
        <div class="mb-5 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl">
            <p class="text-sm text-emerald-700 dark:text-emerald-400">{{ $value }}</p>
        </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:text-white placeholder-gray-400 transition-colors"
                       placeholder="you@example.com">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-red-600 hover:text-red-700 font-medium transition-colors">Forgot password?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required
                       class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:text-white placeholder-gray-400 transition-colors"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                <label for="remember_me" class="text-sm text-gray-600 dark:text-gray-400">Remember me</label>
            </div>

            <button type="submit"
                    class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm mt-2">
                Sign In
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-red-600 hover:text-red-700 font-semibold transition-colors">Create one</a>
        </p>
    </div>
</x-guest-layout>
