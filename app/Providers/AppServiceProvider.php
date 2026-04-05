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

        // 1. Force DB-based cache/session if available to bypass stale file caches
        try {
            if (Schema::hasTable('cache')) {
                config(['cache.default' => 'database']);
                config(['session.driver' => 'database']);
            }
        } catch (\Exception $e) {}

        // 2. Global General Settings with Fail-safe
        $gs = null;
        try {
            if (Schema::hasTable('generalsettings')) {
                $gs = cache()->remember('generalsettings_model', now()->addDay(), function () {
                    return DB::table('generalsettings')->find(1);
                });
            }
        } catch (\Exception $e) {}

        if (!$gs) {
            $gs = new \stdClass();
            $gs->title = "Fabilive";
            $gs->logo = "logo.png";
            $gs->favicon = "favicon.png";
            $gs->is_admin_loader = 0;
            $gs->wholesell = 0;
            $gs->is_capcha = 0;
            $gs->rtl = 0;
            $gs->is_affilite = 0;
        }
        view()->share('gs', $gs);

        // 3. Global Admin Language Fail-safe
        $admin_lang = null;
        try {
            if (Schema::hasTable('admin_languages')) {
                $admin_lang = cache()->remember('admin_language_model_default', now()->addDay(), function () {
                    return DB::table('admin_languages')->where('is_default', 1)->first();
                });
            }
        } catch (\Exception $e) {}
        view()->share('admin_lang', $admin_lang);

        // 4. View Composers for Contextual Variables
        view()->composer('*', function ($settings) use ($gs) {
            $settings->with('gs', $gs);
            
            // Language Fail-safe
            $langg = null;
            try {
                if (Schema::hasTable('languages')) {
                    if (Session::has('language')) {
                        $langg = cache()->remember('language_model_'.Session::get('language'), now()->addDay(), function () {
                            return DB::table('languages')->find(Session::get('language'));
                        });
                    } else {
                        $langg = cache()->remember('language_model_default', now()->addDay(), function () {
                            return DB::table('languages')->where('is_default', 1)->first();
                        });
                    }
                }
            } catch (\Exception $e) {}

            if (!$langg) {
                $langg = new \stdClass();
                $langg->id = 1;
                $langg->name = "English";
                $langg->rtl = 0;
            }
            $settings->with('langg', $langg);

            // Currency Fail-safe
            $curr = null;
            try {
                if (Schema::hasTable('currencies')) {
                    if (Session::has('currency')) {
                        $curr = cache()->remember('currency_model_'.Session::get('currency'), now()->addDay(), function () {
                            return DB::table('currencies')->find(Session::get('currency'));
                        });
                    } else {
                        $curr = cache()->remember('currency_model_default', now()->addDay(), function () {
                            return DB::table('currencies')->where('is_default', 1)->first();
                        });
                    }
                }
            } catch (\Exception $e) {}

            if (!$curr) {
                $curr = new \stdClass();
                $curr->id = 1;
                $curr->name = "CFA";
                $curr->sign = "CFA";
                $curr->value = 1;
            }
            $settings->with('curr', $curr);

            // Settings Tables Fail-safe
            $ps = null; $seo = null; $social = null;
            try {
                if (Schema::hasTable('pagesettings')) $ps = DB::table('pagesettings')->first();
                if (Schema::hasTable('seotools')) $seo = DB::table('seotools')->first();
                if (Schema::hasTable('socialsettings')) $social = DB::table('socialsettings')->first();
            } catch (\Exception $e) {}

            $settings->with('ps', $ps);
            $settings->with('seo', $seo);
            $settings->with('socialsetting', $social);

            // Font Fail-safe
            $font = null;
            try {
                if (Schema::hasTable('fonts')) $font = DB::table('fonts')->where('is_default', 1)->first();
            } catch (\Exception $e) {}
            $settings->with('default_font', $font);

            // Blog Fail-safe
            $blogs = [];
            try {
                if (Schema::hasTable('blogs')) $blogs = DB::table('blogs')->orderby('id', 'desc')->limit(3)->get();
            } catch (\Exception $e) {}
            $settings->with('footer_blogs', $blogs);

            // Extra session variables
            $settings->with('visited', Session::has('visited') ? 0 : 1);
            if (!Session::has('visited')) Session::put('visited', 1);
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
