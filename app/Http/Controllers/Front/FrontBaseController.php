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
        try {
            $this->ps = cache()->remember('pagesettings', now()->addDay(), function () {
                \DB::connection()->getPdo();
                return DB::table('pagesettings')->first();
            });
            if (!$this->ps) throw new \Exception('No settings');
        } catch (\Exception $e) {
            $this->ps = (object)[
                'contact_email' => 'contact@fabilive.com',
                'contact_title' => 'Contact Us',
                'contact_text' => 'Contact Us',
                'side_text' => 'Fabilive',
                'faq_title' => 'FAQ',
                'faq_subtitle' => 'Frequently Asked Questions',
            ];
        }

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

            return $next($request);
        });
    }
}
