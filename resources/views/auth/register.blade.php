<x-guest-layout>
    <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 p-8">

        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Create an account</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Join {{ config('app.name') }} today</p>
        </div>

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl">
            @foreach($errors->all() as $error)
            <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:text-white placeholder-gray-400 transition-colors"
                       placeholder="John Doe">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:text-white placeholder-gray-400 transition-colors"
                       placeholder="you@example.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:text-white placeholder-gray-400 transition-colors"
                       placeholder="Min. 8 characters">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:text-white placeholder-gray-400 transition-colors"
                       placeholder="••••••••">
            </div>

            @if(Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="flex items-start gap-2.5">
                <input id="terms" type="checkbox" name="terms" required
                       class="w-4 h-4 mt-0.5 rounded border-gray-300 text-red-600 focus:ring-red-500 shrink-0">
                <label for="terms" class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                        'terms_of_service' => '<a href="'.route('terms.show').'" target="_blank" class="text-red-600 hover:underline font-medium">'.__('Terms of Service').'</a>',
                        'privacy_policy'   => '<a href="'.route('policy.show').'" target="_blank" class="text-red-600 hover:underline font-medium">'.__('Privacy Policy').'</a>',
                    ]) !!}
                </label>
            </div>
            @endif

            <button type="submit"
                    class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm mt-2">
                Create Account
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-red-600 hover:text-red-700 font-semibold transition-colors">Sign in</a>
        </p>
    </div>
</x-guest-layout>
