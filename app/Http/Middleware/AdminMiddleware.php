<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if (!in_array($user->type, ['admin', 'ops'])) {
            return redirect('/login')->with('message', 'Login to access the Panel');
        }

        return $next($request);
    }
}
