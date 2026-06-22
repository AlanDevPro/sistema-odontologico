<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User; // Importado para la validación estricta
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Importado para verificar contraseñas en Bcrypt/Argon
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        // Limitadores de tasa de peticiones (Rate Limiting)
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });

        // ========================================================================
        // FILTRO DE SEGURIDAD: AUTENTICACIÓN EXCLUSIVA PARA DOCTORES (ORACLE 19c)
        // ========================================================================
        Fortify::authenticateUsing(function (Request $request) {
            // Buscar al usuario por el correo del login
            $user = User::where('email', $request->email)->first();

            // Validación encadenada de alta seguridad
            if ($user && 
                Hash::check($request->password, $user->password) && 
                $user->doctor()->exists() &&               // 1. Debe existir obligatoriamente en la tabla DOCTOR
                $user->doctor->estado === 1) {             // 2. Su estado clínico debe ser Activo (1)
                
                return $user; // Credenciales válidas y es un Doctor autorizado
            }

            // Si falla cualquier condición, se deniega la sesión (Retorna un mensaje genérico por seguridad)
            return null; 
        });
    }
}