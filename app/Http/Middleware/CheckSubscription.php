<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Admins bypass subscription checks
        if ($user && $user->is_admin) {
            return $next($request);
        }
        
        // Check if user has an active subscription
        $activeSubscription = $user->activeSubscription();
        
        if (!$activeSubscription) {
            // Redirect to subscription page or show upgrade message
            return redirect()->route('dashboard')
                ->with('error', 'You need an active subscription to access this feature.');
        }
        
        return $next($request);
    }
} 