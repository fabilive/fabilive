<?php

namespace App\Http\Controllers\Vendor;

use App;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;

class VendorBaseController extends Controller
{
    protected $gs;

    protected $curr;

    protected $language;

    protected $user;

    public function __construct()
    {
        $this->middleware('auth');

        // Test database connectivity
        $dbAvailable = false;
        try {
            \DB::connection()->getPdo();
            $dbAvailable = true;
        } catch (\Exception $e) {
            \Log::warning('VendorBaseController: Database unreachable — using in-memory defaults.');
        }

        // Set Global GeneralSettings with absolute fail-safe
        $this->gs = \App\Models\Generalsetting::safeFirst();

        $this->middleware(function ($request, $next) {
            // Set Global Users
            $this->user = Auth::user();

            // Set Global Language with fail-safe
            $this->language = null;
            try {
                if (Session::has('language')) {
                    $this->language = DB::table('languages')->find(Session::get('language'));
                } else {
                    $this->language = DB::table('languages')->where('is_default', '=', 1)->first();
                }
            } catch (\Exception $e) {}

            // Fallback if language is still null
            if (!$this->language) {
                $this->language = new \stdClass();
                $this->language->name = "English";
                $this->language->id = 1;
                $this->language->file = "english.json";
                $this->language->rtl = 0;
            }

            view()->share('langg', $this->language);
            view()->share('admin_lang', $this->language); // Shared for layouts
            App::setlocale($this->language->name ?? 'English');

            // Set Global Currency with fail-safe
            $this->curr = null;
            try {
                $this->curr = DB::table('currencies')->where('is_default', '=', 1)->first();
            } catch (\Exception $e) {}
            
            if (!$this->curr) {
                $this->curr = new \stdClass();
                $this->curr->name = "CFA";
                $this->curr->sign = "CFA";
                $this->curr->value = 1;
            }

            view()->share('curr', $this->curr);
            view()->share('gs', $this->gs);

            return $next($request);
        });
    }
}
