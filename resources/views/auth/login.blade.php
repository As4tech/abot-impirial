<x-guest-layout>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
        <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-slate-900 to-slate-700 rounded-l-lg">
            <div class="text-center px-8 py-12 text-slate-100">
                <div class="flex items-center justify-center gap-3 mb-4">
                    @if(function_exists('setting') && setting('general.logo'))
                        <img src="{{ setting('general.logo') }}" alt="Logo" class="h-12 w-auto">
                    @endif
                    <div class="text-2xl font-semibold">{{ function_exists('setting') ? setting('general.business_name', config('app.name')) : config('app.name') }}</div>
                </div>
                <div class="text-sm opacity-80">Welcome back. Please sign in to continue.</div>
            </div>
        </div>

        <div class="bg-white rounded-r-lg p-6 md:p-10">
            <div class="md:hidden mb-6 text-center">
                <div class="inline-flex items-center gap-2">
                    @if(function_exists('setting') && setting('general.logo'))
                        <img src="{{ setting('general.logo') }}" alt="Logo" class="h-10 w-auto">
                    @endif
                    <div class="text-lg font-semibold">{{ function_exists('setting') ? setting('general.business_name', config('app.name')) : config('app.name') }}</div>
                </div>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input id="email" class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-slate-500 focus:outline-none" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Password</label>
                    <input id="password" class="mt-1 w-full border rounded px-3 py-2 focus:ring-2 focus:ring-slate-500 focus:outline-none" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-slate-700 focus:ring-slate-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-slate-700 hover:underline" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <button class="w-full bg-slate-900 hover:bg-slate-800 text-white rounded px-4 py-2 font-semibold transition">Log in</button>
            </form>
        </div>
    </div>
</x-guest-layout>
