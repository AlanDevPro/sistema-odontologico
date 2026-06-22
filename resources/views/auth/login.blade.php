<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- Logo y título principal -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <!-- Reemplaza esto con tu logo -->
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-2xl">L</span>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">LALYS</h2>
                <p class="text-sm text-gray-500 font-medium">CONSULTORIO DENTAL</p>
            </div>

            <!-- Título del formulario -->
            <h3 class="text-lg font-semibold text-gray-700 text-center mb-6">
                Ingreso al sistema de información web
            </h3>

            <!-- Errores de validación -->
            <x-validation-errors class="mb-4" />

            <!-- Mensaje de estado -->
            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $value }}
                </div>
            @endsession

            <!-- Formulario de login -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Campo Email -->
                <div class="mb-4">
                    <x-label for="email" value="{{ __('Email') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="email" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="example@email.com"
                    />
                </div>

                <!-- Campo Password -->
                <div class="mb-4">
                    <x-label for="password" value="{{ __('Password') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="password" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                </div>

                <!-- Checkbox Remember me -->
                <div class="block mb-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col space-y-3 mt-6">
                    <!-- Botón Ingresar -->
                    <x-button class="w-full justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md transition duration-150 ease-in-out">
                        {{ __('INGRESAR') }}
                    </x-button>

                    <!-- Enlaces inferiores -->
                    <div class="flex justify-between items-center text-sm">
                        @if (Route::has('password.request'))
                            <a class="text-blue-600 hover:text-blue-800 hover:underline" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif

                        <a class="text-blue-600 hover:text-blue-800 hover:underline" href="{{ route('register') }}">
                            {{ __('¿No tienes cuenta? Regístrate') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>