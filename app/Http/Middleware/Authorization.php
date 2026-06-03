<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next) {

        if (!Auth::check() || Auth::user()->role !== 1) {
            Auth::logout();
            return redirect()
                ->route('admin.login')
                ->with('error', 'You do not have permission to access admin panel');
        }

        return $next($request);
    }
}
