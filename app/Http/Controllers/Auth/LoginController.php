<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override redirect after login
     */
    protected function redirectTo()
    {
        if (Auth::user()->role === 'admin') {
            return '/home';
        }

        // selain admin (user, customer, dll) diarahkan ke intended
        return session()->get('url.intended', '/');
    }
}
