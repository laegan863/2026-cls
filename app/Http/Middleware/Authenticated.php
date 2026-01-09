<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticated
{
    /**
     * Handle an incoming request.
     * Redirect to login if user is not authenticated.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Please login to access this page.');
        }

        // Check if user is active
        if (!Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('auth.login')
                ->with('error', 'Your account has been deactivated. Please contact administrator.');
        }

        return $next($request);
    }
}
