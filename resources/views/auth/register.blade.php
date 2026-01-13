<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-neutral-900">Create your account</h2>
        <p class="mt-2 text-sm text-neutral-600">Join SproutLMS and start your learning journey</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full name')" />
            <x-text-input 
                id="name" 
                class="block mt-2 w-full" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

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
                autocomplete="username"
                placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role -->
        <div>
            <x-input-label for="role" :value="__('I want to')" />
            <select 
                id="role" 
                name="role" 
                class="block mt-2 w-full rounded-md border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-neutral-900" 
                required>
                <option value="">Select your role...</option>
                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Learn (Student)</option>
                <option value="instructor" {{ old('role') === 'instructor' ? 'selected' : '' }}>Teach (Instructor)</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
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
                {{ __('Create account') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-neutral-600">
                Already have an account?
                <a 
                    href="{{ route('login') }}" 
                    class="font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>

