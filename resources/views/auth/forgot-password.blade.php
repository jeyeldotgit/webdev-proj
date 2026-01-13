<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-neutral-900">Reset your password</h2>
        <p class="mt-2 text-sm text-neutral-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-medium">
                {{ __('Send reset link') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <a 
                href="{{ route('login') }}" 
                class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                ← {{ __('Back to login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
