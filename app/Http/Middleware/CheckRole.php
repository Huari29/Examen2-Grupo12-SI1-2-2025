<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Verificar si el usuario está activo
        if (!$user->activo) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tu cuenta ha sido desactivada.');
        }

        // Si no tiene rol asignado
        if (!$user->rol) {
            abort(403, 'No tienes un rol asignado.');
        }

        // Verificar si el usuario tiene alguno de los roles permitidos
        foreach ($roles as $role) {
            if ($user->rol->nombre === $role) {
                return $next($request);
            }
        }

        // Si no tiene el rol requerido
        abort(403, 'No tienes permisos para acceder a esta sección.');
    }
}