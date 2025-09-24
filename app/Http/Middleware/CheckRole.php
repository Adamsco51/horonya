<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Vérifie si l'utilisateur a le rôle requis
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur est actif
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Votre compte n\'est pas activé. Contactez un administrateur.');
        }

        // Vérifier si l'utilisateur a le rôle requis
        if (!auth()->user()->hasRole($role)) {
            abort(403, 'Accès refusé. Vous n\'avez pas le rôle nécessaire.');
        }

        return $next($request);
    }
}
