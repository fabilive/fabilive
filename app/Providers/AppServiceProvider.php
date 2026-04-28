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

        // Test database connectivity once — skip all DB operations if unreachable
        $dbAvailable = \App\Models\Generalsetting::isDbValid();

        // 2. Global General Settings with Fail-safe
        $gs = \App\Models\Generalsetting::safeFirst();
        view()->share('gs', $gs);

        // 3. Global Admin Language Fail-safe
        $admin_lang = null;
        if ($dbAvailable) {
            try {
                $admin_lang = cache()->remember('admin_language_model_default', now()->addDay(), function () {
                    return DB::table('admin_languages')->where('is_default', 1)->first();
                });
            } catch (\Exception $e) {}
        }
        view()->share('admin_lang', $admin_lang);

        // 4. View Composers for Contextual Variables
        view()->composer('*', function ($settings) use ($gs, $dbAvailable) {
            $settings->with('gs', $gs);
            
            // Language Fail-safe
            $langg = null;
            if ($dbAvailable) {
                try {
                    if (Session::has('language')) {
                        $langg = cache()->remember('language_model_'.Session::get('language'), now()->addDay(), function () {
                            return DB::table('languages')->find(Session::get('language'));
                        });
                    } else {
                        $langg = cache()->remember('language_model_default', now()->addDay(), function () {
                            return DB::table('languages')->where('is_default', 1)->first();
                        });
                    }
                } catch (\Exception $e) {}
            }

            if (!$langg) {
                $langg = new \stdClass();
                $langg->id = 1;
                $langg->name = "English";
                $langg->rtl = 0;
            }
            $settings->with('langg', $langg);

            // Currency Fail-safe (Forced to CFA)
            $curr = null;
            if ($dbAvailable) {
                try {
                    $curr = cache()->remember('currency_model_cfa', now()->addDay(), function () {
                        return DB::table('currencies')->where('name', 'CFA')->first();
                    });
                } catch (\Exception $e) {}
            }

            if (!$curr) {
                $curr = new \stdClass();
                $curr->id = 12; // Assuming 12 based on previous context, but fail-safe below handles it
                $curr->name = "CFA";
                $curr->sign = "CFA";
                $curr->value = 1;
            }
            $settings->with('curr', $curr);

            // Settings Tables Fail-safe
            $ps = \App\Models\Pagesetting::safeFirst();
            $seo = null;
            $social = null;

            if ($dbAvailable) {
                try {
                    $seo = DB::table('seotools')->first();
                    $social = DB::table('socialsettings')->first();
                } catch (\Exception $e) {}
            }

            $settings->with('ps', $ps);
            $settings->with('seo', $seo);
            $settings->with('socialsetting', $social);

            // Font Fail-safe
            $font = null;
            if ($dbAvailable) {
                try {
                    $font = DB::table('fonts')->where('is_default', 1)->first();
                } catch (\Exception $e) {}
            }
            $settings->with('default_font', $font);

            // Blog Fail-safe
            $blogs = collect();
            if ($dbAvailable) {
                try {
                    $blogs = DB::table('blogs')->orderby('id', 'desc')->limit(3)->get();
                } catch (\Exception $e) {}
            }
            $settings->with('footer_blogs', $blogs);

            // Fail-safe global collections for Blade templates
            $categories = collect();
            $pages = collect();
            $social_links = collect();
            $partners = collect();
            $services = collect();

            if ($dbAvailable) {
                try {
                    $categories = cache()->remember('global_categories', now()->addDay(), function() {
                        return \App\Models\Category::with('subs')
                            ->where('status', 1)
                            ->whereNotIn('name', ['Food', 'Drinks'])
                            ->get();
                    });
                } catch (\Exception $e) {}

                try {
                    $pages = cache()->remember('global_pages', now()->addDay(), function() {
                        return \App\Models\Page::get();
                    });
                } catch (\Exception $e) {}

                try {
                    $social_links = DB::table('social_links')->where('user_id', 0)->where('status', 1)->get();
                } catch (\Exception $e) {}

                try {
                    $partners = cache()->remember('global_partners', now()->addDay(), function() {
                        return DB::table('partners')->get();
                    });
                } catch (\Exception $e) {}

                try {
                    $services = cache()->remember('global_services', now()->addDay(), function() {
                        return DB::table('services')->where('user_id','=',0)->get();
                    });
                } catch (\Exception $e) {}
            }

            $settings->with('global_categories', $categories);
            $settings->with('global_pages', $pages);
            $settings->with('global_social_links', $social_links);
            $settings->with('global_partners', $partners);
            $settings->with('global_services', $services);

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
