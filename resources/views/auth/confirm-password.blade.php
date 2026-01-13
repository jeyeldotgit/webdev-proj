<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-neutral-900">Confirm password</h2>
        <p class="mt-2 text-sm text-neutral-600">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

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
                autofocus
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center py-3 text-base font-medium">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
