<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 px-4 py-12">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-gray-200/60 border border-gray-100 p-8 transition-all duration-300">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Welcome back</h1>
                <p class="mt-2 text-sm text-gray-500">Enter your credentials to access your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-input-error :messages="$errors->all()" class="mb-6" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email address')" class="block text-sm font-medium text-gray-700 mb-1.5" />
                    <x-text-input 
                        id="email" 
                        class="w-full rounded-lg border-gray-300 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500/20 focus:bg-white transition duration-200 py-2.5 px-3.5 text-gray-900 placeholder-gray-400" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="name@company.com"
                    />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700" />
                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <x-text-input 
                        id="password" 
                        class="w-full rounded-lg border-gray-300 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500/20 focus:bg-white transition duration-200 py-2.5 px-3.5 text-gray-900 placeholder-gray-400"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" 
                        placeholder="••••••••"
                    />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        id="remember" 
                        type="checkbox" 
                        name="remember" 
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500/20 transition cursor-pointer"
                    >
                    <label for="remember" class="ml-2.5 block text-sm text-gray-600 cursor-pointer select-none">
                        Remember me for 30 days
                    </label>
                </div>

                <!-- Submit Button -->
                <x-primary-button class="w-full justify-center py-2.5 text-sm font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] transition-all duration-200 shadow-sm shadow-indigo-200/50">
                    {{ __('Log in') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-guest-layout>