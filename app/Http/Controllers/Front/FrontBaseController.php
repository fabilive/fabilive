<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

//use Markury\MarkuryPost;

class FrontBaseController extends Controller
{
    protected $gs;

    protected $ps;

    protected $curr;

    protected $language;

    public function __construct()
    {
        //$this->auth_guests();
        $this->gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });

        // Set Global PageSettings

        $this->ps = cache()->remember('pagesettings', now()->addDay(), function () {
            return DB::table('pagesettings')->first();
        });

        $this->middleware(function ($request, $next) {

            if (Session::has('language')) {
                $this->language = Language::find(Session::get('language'));
            } else {
                $this->language = Language::where('is_default', '=', 1)->first();
            }
            if (! Session::has('language')) {
                $this->language = Language::where('is_default', '=', 1)->first();
            }

            App::setLocale($this->language->name);

            if (Session::has('currency')) {
                $this->curr = Currency::find(Session::get('currency'));
            } else {
                $this->curr = Currency::where('is_default', '=', 1)->first();
            }

            return $next($request);
        });
    }
}
