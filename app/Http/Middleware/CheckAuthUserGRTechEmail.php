<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthUserGRTechEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !str_ends_with(Auth::user()->email, '@grtech.com')) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'You must use a @grtech.com email to log in.']);
        }
        return $next($request);
    }
}
