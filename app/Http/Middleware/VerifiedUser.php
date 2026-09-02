<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Not logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // Super Admin can always access
        if ($user->hasRole([
            'super-admin', 
            'blotter-officer', 
            'resident-officer',
            ])) {
            return $next($request);
        }

        // Verified/linked member can access
        if ($user->hasRole('member') && $user->resident_id) {
            return $next($request);
        }

        // Everyone else
        return redirect()->route('verifications.create')
            ->with('error', 'You must verify your account before accessing this page.');
    }
}