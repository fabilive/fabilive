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
        $this->gs = \App\Models\Generalsetting::safeFirst();

        // Set Global PageSettings
        $this->ps = \App\Models\Pagesetting::safeFirst();

        $this->middleware(function ($request, $next) {

            $this->language = null;
            try {
                if (Session::has('language')) {
                    $this->language = Language::find(Session::get('language'));
                } else {
                    $this->language = Language::where('is_default', '=', 1)->first();
                }
            } catch (\Exception $e) {}

            if (!$this->language) {
                $this->language = new \stdClass();
                $this->language->name = "English";
                $this->language->id = 1;
                $this->language->file = "english.json";
                $this->language->rtl = 0;
            }

            App::setLocale($this->language->name ?? 'English');

            $this->curr = null;
            try {
                if (Session::has('currency')) {
                    $this->curr = Currency::find(Session::get('currency'));
                } else {
                    $this->curr = Currency::where('is_default', '=', 1)->first();
                }
            } catch (\Exception $e) {}

            if (!$this->curr) {
                $this->curr = new \stdClass();
                $this->curr->name = "CFA";
                $this->curr->sign = "CFA";
                $this->curr->value = 1;
            }

            // Force value to 1 for CFA/XFA to avoid unintended conversions
            if (isset($this->curr->sign) && in_array($this->curr->sign, ['CFA', 'XFA'])) {
                $this->curr->value = 1;
            }

            return $next($request);
        });
    }
}
