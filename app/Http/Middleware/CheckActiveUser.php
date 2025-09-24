<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     * Vérifie que l'utilisateur connecté est actif
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Vérifier si l'utilisateur est inactif
            if (!$user->is_active) {
                Auth::logout();
                
                return redirect()->route('login')->with('error', 'Votre compte est inactif. Veuillez contacter un administrateur.');
            }
        }

        return $next($request);
    }
}
