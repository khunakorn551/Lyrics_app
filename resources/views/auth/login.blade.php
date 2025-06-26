<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-indigo-100 via-blue-100 to-white">
        <div class="mb-6 flex justify-center">
            <x-application-logo class="h-20 w-auto" />
        </div>
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 mt-2">
            <h2 class="text-2xl font-bold text-center text-indigo-700 mb-6">Login</h2>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition ms-3">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="inline-block px-4 py-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg font-medium transition">Don't have an account? Register</a>
            </div>
        </div>
    </div>
</x-guest-layout>
