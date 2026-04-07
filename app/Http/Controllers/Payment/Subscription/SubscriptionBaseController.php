<?php

namespace App\Http\Controllers\Payment\Subscription;

use App;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;

class SubscriptionBaseController extends Controller
{
    protected $gs;

    protected $curr;

    protected $language;

    protected $user;

    public function __construct()
    {
        // Set Global GeneralSettings
        $this->gs = \App\Models\Generalsetting::safeFirst();

        $this->middleware(function ($request, $next) {

            // Set Global Users
            $this->user = Auth::user();

            $this->language = null;
            try {
                if (Session::has('language')) {
                    $this->language = DB::table('languages')->find(Session::get('language'));
                } else {
                    $this->language = DB::table('languages')->where('is_default', '=', 1)->first();
                }
            } catch (\Exception $e) {}

            if (!$this->language) {
                $this->language = new \stdClass();
                $this->language->name = "English";
                $this->language->id = 1;
                $this->language->file = "english.json";
                $this->language->rtl = 0;
            }

            view()->share('langg', $this->language);
            App::setlocale($this->language->name ?? 'English');

            $this->curr = null;
            try {
                if (Session::has('currency')) {
                    $this->curr = DB::table('currencies')->find(Session::get('currency'));
                } else {
                    $this->curr = DB::table('currencies')->where('is_default', '=', 1)->first();
                }
            } catch (\Exception $e) {}

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
