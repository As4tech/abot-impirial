<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            
            <!-- Logo / Title -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Welcome Back</h1>
                <p class="text-sm text-gray-500">Sign in to your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-input-error :messages="$errors->all()" class="mb-4" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input 
                        id="email" 
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" 
                    />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input 
                        id="password" 
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" 
                    />
                </div>

                <!-- Remember Me + Forgot -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm text-gray-600">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        >
                        <span class="ml-2">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <x-primary-button class="w-full justify-center py-2.5 text-sm font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 transition">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

            </form>

        </div>

    </div>
</x-guest-layout>