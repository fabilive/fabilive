<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\Font;
use App\Models\Language;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Paginator::useBootstrap();

        // V75-V83: GLOBAL CONFIG FORCE (Override stale config cache immediately)
        try {
            if (Schema::hasTable('cache')) {
                config(['cache.default' => 'database']);
                config(['session.driver' => 'database']);
            }
        } catch (\Exception $e) {
        }

        // Always force reCAPTCHA from env()
        config(['nocaptcha.secret' => env('NOCAPTCHA_SECRET', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFojJ4WifJWeE')]);
        config(['nocaptcha.sitekey' => env('NOCAPTCHA_SITEKEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI')]);

        // FAIL-SAFE: If the filesystem is locked, force array cache if DB not available
        if (config('cache.default') === 'file') {
            try {
                if (! is_writable(storage_path('framework/cache/data'))) {
                    if (! Schema::hasTable('cache')) {
                        config(['cache.default' => 'array']);
                    }
                }
            } catch (\Exception $e) {
                config(['cache.default' => 'array']);
            }
        }

        $gs = null;
        try {
            $gs = cache()->remember('generalsettings_model', now()->addDay(), function () {
                return \App\Models\Generalsetting::find(1);
            });
        } catch (\Exception $e) {
            $gs = \App\Models\Generalsetting::find(1);
        }

        // Absolute fail-safe for missing properties
        if ($gs) {
            $gs->is_capcha = 0; // Force disable Captcha globally for recovery
            if (! isset($gs->rtl)) {
                $gs->rtl = 0;
            }
            if (! isset($gs->is_admin_loader)) {
                $gs->is_admin_loader = 0;
            }
            if (! isset($gs->is_verification_email)) {
                $gs->is_verification_email = 0;
            }
            if (! isset($gs->is_guest_checkout)) {
                $gs->is_guest_checkout = 0;
            }
            if (! isset($gs->wholesell)) {
                $gs->wholesell = 0;
            }
            if (! isset($gs->verify_product)) {
                $gs->verify_product = 0;
            }
            if (! isset($gs->is_affilate)) {
                $gs->is_affilate = 0;
            }
            if (! isset($gs->affilate_charge)) {
                $gs->affilate_charge = 0;
            }

            // Logo file existence fallback — gracefully handle in memory only (don't update DB)
            $logoPath = public_path('assets/images/'.($gs->logo ?? ''));
            if (empty($gs->logo) || ! file_exists($logoPath)) {
                $gs->logo = 'logo.png';
            }

            // Footer logo fallback — gracefully handle in memory only
            if (! empty($gs->footer_logo)) {
                $footerLogoPath = public_path('assets/images/'.$gs->footer_logo);
                if (! file_exists($footerLogoPath)) {
                    $gs->footer_logo = 'logo.png';
                }
            }
        }

        view()->composer('*', function ($settings) use ($gs) {
            $settings->with('gs', $gs);

            try {
                $ps = cache()->remember('pagesettings', now()->addDay(), function () {
                    return DB::table('pagesettings')->first();
                });
                $seo = cache()->remember('seotools', now()->addDay(), function () {
                    return DB::table('seotools')->first();
                });
                $social = cache()->remember('socialsettings', now()->addDay(), function () {
                    return DB::table('socialsettings')->first();
                });
            } catch (\Exception $e) {
                $ps = DB::table('pagesettings')->first();
                $seo = DB::table('seotools')->first();
                $social = DB::table('socialsettings')->first();
            }

            $settings->with('ps', $ps);
            $settings->with('seo', $seo);
            $settings->with('socialsetting', $social);

            $settings->with('default_font', cache()->remember('default_font', now()->addDay(), function () {
                return Font::whereIsDefault(1)->first();
            }));

            if (Session::has('currency')) {
                $settings->with('curr', Currency::find(Session::get('currency')));
            } else {
                $settings->with('curr', Currency::where('is_default', '=', 1)->first());
            }

            if (Session::has('language')) {
                $settings->with('langg', Language::find(Session::get('language')));
            } else {
                $settings->with('langg', Language::where('is_default', '=', 1)->first());
            }

            if (! Session::has('visited')) {
                Session::put('visited', 1);
                $visited = 1;
            } else {
                $visited = 0;
            }

            $settings->with('visited', $visited);

            $settings->with('footer_blogs', DB::table('blogs')->orderby('id', 'desc')->limit(3)->get());
        });
    }

    public function register()
    {
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }
}
