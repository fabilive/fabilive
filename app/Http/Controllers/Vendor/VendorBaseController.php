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

        // Set Global GeneralSettings with absolute fail-safe
        try {
            $this->gs = DB::table('generalsettings')->find(1);
        } catch (\Exception $e) {
            $this->gs = null;
        }

        if (!$this->gs) {
            $this->gs = new \stdClass();
            $this->gs->title = "Fabilive";
            $this->gs->is_admin_loader = 0;
            $this->gs->wholesell = 0;
            $this->gs->logo = "logo.png";
            $this->gs->favicon = "favicon.png";
            $this->gs->vendor_ship_info = 1;
            $this->gs->affilite = 0;
        }

        $this->middleware(function ($request, $next) {

            // Set Global Users
            $this->user = Auth::user();

            // Set Global Language with fail-safe
            try {
                if (Session::has('language')) {
                    $this->language = DB::table('languages')->find(Session::get('language'));
                } else {
                    $this->language = DB::table('languages')->where('is_default', '=', 1)->first();
                }
            } catch (\Exception $e) {
                $this->language = null;
            }

            // Fallback if language is still null
            if (!$this->language) {
                $this->language = new \stdClass();
                $this->language->name = "English";
                $this->language->id = 1;
                $this->language->file = "english.json";
            }

            view()->share('langg', $this->language);
            App::setlocale($this->language->name ?? 'English');

            // Set Global Currency with fail-safe
            try {
                $this->curr = DB::table('currencies')->where('is_default', '=', 1)->first();
            } catch (\Exception $e) {
                $this->curr = null;
            }
            
            if (!$this->curr) {
                $this->curr = new \stdClass();
                $this->curr->name = "CFA";
                $this->curr->sign = "CFA";
                $this->curr->value = 1;
            }

            return $next($request);
        });
    }
}
