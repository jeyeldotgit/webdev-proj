<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-neutral-900">Set new password</h2>
        <p class="mt-2 text-sm text-neutral-600">Enter your new password below</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input 
                id="email" 
                class="block mt-2 w-full" 
                type="email" 
                name="email" 
                :value="old('email', $request->email)" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('New password')" />
            <x-text-input 
                id="password" 
                class="block mt-2 w-full" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input 
                id="password_confirmation" 
                class="block mt-2 w-full"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-medium">
                {{ __('Reset password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
