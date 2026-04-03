<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Permissions
{
    public function handle($request, Closure $next, $data)
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            if ($admin->IsSuper()) {
                return $next($request);
            }
            if ($admin->sectionCheck($data)) {
                return $next($request);
            }
        }

        return redirect()->route('admin.dashboard')->with('unsuccess', "You don't have access to that section");
    }
}
