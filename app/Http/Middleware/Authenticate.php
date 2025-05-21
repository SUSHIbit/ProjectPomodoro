<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Make sure we're only redirecting non-JSON requests
        if ($request->expectsJson()) {
            return null;
        }
        
        // Add a safeguard in case the login route is not defined
        if (! \Route::has('login')) {
            return '/login'; // Fallback to a direct path
        }
        
        return route('login');
    }
}