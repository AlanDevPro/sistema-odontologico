<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-2xl">L</span>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">LALYS</h2>
                <p class="text-sm text-gray-500 font-medium">CONSULTORIO DENTAL</p>
            </div>

            <h3 class="text-lg font-semibold text-gray-700 text-center mb-6">
                Registro de Personal Médico (Doctores)
            </h3>

            <x-validation-errors class="mb-4" />

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $value }}
                </div>
            @endsession

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <x-label for="name" value="{{ __('Nombre Completo') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="name" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Ej. Juan Pérez"
                    />
                </div>

                <div class="mb-4">
                    <x-label for="ci_dni" value="{{ __('Documento de Identidad (CI / DNI)') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="ci_dni" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        type="text" 
                        name="ci_dni" 
                        :value="old('ci_dni')" 
                        required 
                        placeholder="Ej. 1234567"
                    />
                </div>

                <div class="mb-4">
                    <x-label for="especialidad" value="{{ __('Especialidad Médica') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="especialidad" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        type="text" 
                        name="especialidad" 
                        :value="old('especialidad')" 
                        required 
                        placeholder="Ej. Odontopediatría, Ortodoncia"
                    />
                </div>

                <div class="mb-4">
                    <x-label for="telefono" value="{{ __('Teléfono / Celular') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="telefono" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        type="text" 
                        name="telefono" 
                        :value="old('telefono')" 
                        placeholder="Ej. 71234567"
                    />
                </div>

                <div class="mb-4">
                    <x-label for="email" value="{{ __('Correo Electrónico') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="email" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autocomplete="username"
                        placeholder="doctor@lalysdent.com"
                    />
                </div>

                <div class="mb-4">
                    <x-label for="password" value="{{ __('Contraseña') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="password" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>

                <div class="mb-4">
                    <x-label for="password_confirmation" value="{{ __('Confirmar Contraseña') }}" class="block text-sm font-medium text-gray-700 mb-1" />
                    <x-input 
                        id="password_confirmation" 
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mb-4">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox 
                                    id="terms" 
                                    name="terms" 
                                    required 
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" 
                                />
                                <div class="ms-2 text-sm text-gray-600">
                                    {!! __('Acepto los :terms_of_service y la :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-blue-600 hover:text-blue-800 hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">'.__('Términos de Servicio').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-blue-600 hover:text-blue-800 hover:underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">'.__('Política de Privacidad').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                <div class="flex flex-col space-y-3 mt-6">
                    <x-button class="w-full justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md transition duration-150 ease-in-out">
                        {{ __('REGISTRAR DOCTOR') }}
                    </x-button>

                    <div class="flex justify-center items-center text-sm">
                        <span class="text-gray-600">{{ __('¿Ya tienes cuenta?') }}</span>
                        <a class="ms-2 text-blue-600 hover:text-blue-800 hover:underline font-medium" href="{{ route('login') }}">
                            {{ __('Inicia sesión aquí') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>