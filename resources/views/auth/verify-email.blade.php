<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-neutral-900">Verify your email</h2>
        <p class="mt-2 text-sm text-neutral-600">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-primary-50 border border-primary-200 rounded-lg">
            <p class="text-sm font-medium text-primary-800">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </p>
        </div>
    @endif

    <div class="space-y-6">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center py-3 text-base font-medium">
                {{ __('Resend verification email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit" 
                class="w-full text-center text-sm font-medium text-neutral-600 hover:text-neutral-900 transition-colors py-2">
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
