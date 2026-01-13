<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-neutral-900">Welcome back</h2>
        <p class="mt-2 text-sm text-neutral-600">Sign in to your account to continue</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input 
                id="email" 
                class="block mt-2 w-full" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input 
                id="password" 
                class="block mt-2 w-full"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded border-neutral-300 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-2" 
                    name="remember">
                <span class="ms-2 text-sm text-neutral-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a 
                    class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors" 
                    href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-medium">
                {{ __('Sign in') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-neutral-600">
                Don't have an account?
                <a 
                    href="{{ route('register') }}" 
                    class="font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    {{ __('Sign up') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
