<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsEmployer
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Check if the user is logged in AND their role is employer
    if ($request->user() && $request->user()->role === 'employer') {
        return $next($request);
    }

    // If they aren't an employer, send them back to the seeker dashboard
    return redirect()->route('dashboard');
}
}
