<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            switch ($guard) {
                case 'admin':
                    if (Auth::guard($guard)->check()) {
                        return redirect()->route('admin.dashboard');
                    }
                    break;

                case 'rider':
                    if (Auth::guard($guard)->check()) {
                        return redirect()->route('rider-dashboard');
                    }
                    break;

                default:
                    if (Auth::guard($guard)->check()) {
                        if ($guard === 'admin') {
                            return redirect()->route('admin.dashboard');
                        }
                        if ($guard === 'rider') {
                            return redirect()->route('rider-dashboard');
                        }

                        return redirect()->route('user-dashboard');
                    }
                    break;
            }

            return $next($request);
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->is('admin/debug*')) {
                return response()->json([
                    'error_in_middleware' => 'RedirectIfAuthenticated',
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'guard' => $guard
                ], 500);
            }
            // Temporarily throw standard exception to break and see the Laravel error trace if debug comes up, 
            // or return basic text to ensure no 500 blank page
            throw $e;
        }
    }
}
