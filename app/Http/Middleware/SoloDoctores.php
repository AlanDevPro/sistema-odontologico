<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SoloDoctores
{
    /**
     * Manejar la petición entrante asegurando que pertenezca a un doctor activo.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();

            // Si NO tiene registro en la tabla DOCTOR o su estado no es activo (1)
            if (!$user->doctor || $user->doctor->estado != 1) {
                
                // 1. Cierra la sesión en el guard web clásico de forma segura si existe
                if (method_exists(Auth::guard(), 'logout')) {
                    Auth::guard()->logout();
                }

                // 2. Invalidar y regenerar el token de la sesión de Laravel (Forma estándar y robusta)
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // 3. Redirigir al login con el mensaje flash de error
                return redirect()->route('login')->with('error', 'Acceso denegado. Este panel es exclusivo para personal médico activo.');
            }
        }

        return $next($request);
    }
}