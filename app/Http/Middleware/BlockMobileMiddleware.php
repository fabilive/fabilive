<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Schema;

class BlockMobileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $gs = Generalsetting::first();
        
        // Only proceed if the column exists and is enabled
        if ($gs && Schema::hasColumn('generalsettings', 'block_mobile_browser') && $gs->block_mobile_browser) {
            
            // Allow admin routes to pass through so admins can still access from mobile
            if ($request->is('admin') || $request->is('admin/*')) {
                return $next($request);
            }
            
            // Simple mobile detection regex
            $userAgent = $request->header('User-Agent');
            $isMobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $userAgent);
            
            if ($isMobile) {
                // If it's an API request, return JSON
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Mobile browser access is temporarily disabled. Please download our app.'
                    ], 403);
                }
                
                // Return a simple friendly view (you can create resources/views/errors/mobile_blocked.blade.php)
                return response(view('errors.mobile_blocked'));
            }
        }
        
        return $next($request);
    }
}
