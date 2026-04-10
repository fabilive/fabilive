<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Vendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->IsVendor()) {
                $user = Auth::user();
                if ($user->checkWarning()) {
                    $warningVerify = $user->verifies()->where('admin_warning', '=', '1')->latest('id')->first();
                    if ($warningVerify) {
                        // Allow access to the verification submission, the warning page itself, and logout
                        $allowed_routes = [
                            'vendor-warning',
                            'vendor-verify',
                            'vendor-verify-submit',
                            'user-logout'
                        ];
                        
                        if (!in_array($request->route()->getName(), $allowed_routes)) {
                            return redirect()->route('vendor-warning', $warningVerify->id);
                        }
                    }
                }
                return $next($request);
            }
        }

        return redirect()->back();
    }
}
