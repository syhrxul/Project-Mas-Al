<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // If user has admin role, redirect to admin dashboard
        if (auth()->user()->hasRole('admin')) {
            return redirect('/admin')->with('error', 'Admin users cannot access the user dashboard.');
        }

        // Ensure user has the user role if using role-based permissions
        // Uncomment if you have role checking implemented
        // if (!auth()->user()->hasRole('user')) {
        //     abort(403, 'Unauthorized action.');
        // }

        return $next($request);
    }
}
