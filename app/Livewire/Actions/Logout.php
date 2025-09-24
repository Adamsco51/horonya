<?php

namespace App\Livewire\Actions;

use App\Models\UserLoginLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        // Log de déconnexion avant de déconnecter l'utilisateur
        if (Auth::check()) {
            UserLoginLog::createLog(Auth::id(), 'logout');
        }

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }
}
