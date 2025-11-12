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
        // For API requests, return null to prevent redirect (return JSON instead)
        if ($request->expectsJson()) {
            return null;
        }

        // Only redirect for web routes
        return route('login');
    }
}