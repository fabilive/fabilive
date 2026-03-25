<?php

namespace App\Providers;

use App\Models\Currency;
use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Font;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Paginator::useBootstrap();

        // FAIL-SAFE: If the filesystem is locked, force database or array cache to prevent 500 errors.
        if (config('cache.default') === 'file') {
            try {
                // Check if directory is writeable, if not, force database
                if (!is_writable(storage_path('framework/cache/data'))) {
                    config(['cache.default' => 'database']);
                    config(['session.driver' => 'database']);
                }
            } catch (\Exception $e) {
                config(['cache.default' => 'array']);
            }
        }

        $gs = null;
        try {
            $gs = cache()->remember('generalsettings', now()->addDay(), function () {
                return DB::table('generalsettings')->first();
            });
        } catch (\Exception $e) {
            $gs = DB::table('generalsettings')->first();
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

            
            if (!Session::has('visited')) {
                Session::put('visited', 1);
                $visited = 1;
            } else {
                $visited = 0;
            }

            $settings->with('visited', $visited);
            
            $settings->with('footer_blogs', DB::table('blogs')->orderby('id','desc')->limit(3)->get());
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
