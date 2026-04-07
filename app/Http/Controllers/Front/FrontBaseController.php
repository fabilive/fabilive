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
        try {
            $this->gs = cache()->remember('generalsettings', now()->addDay(), function () {
                return DB::table('generalsettings')->first();
            });
        } catch (\Exception $e) {
            $this->gs = (object)[
                'title' => 'Fabilive',
                'header_email' => 'support@fabilive.com',
                'header_phone' => '+123456789',
                'footer_text' => 'Fabilive',
                'copyright_text' => '© 2026 Fabilive',
                'is_capcha' => 0,
                'is_verification_email' => 0,
                'is_affilite' => 0,
                'affilite' => 0,
                'physical' => 1,
                'digital' => 1,
                'license' => 1,
                'listing' => 1,
                'vendor_ship_info' => 0,
                'is_provider' => 0,
                'guest_checkout' => 1,
                'currency_format' => 0,
                'withdraw_fee' => 0,
                'withdraw_charge' => 0,
                'is_maintain' => 0,
                'is_slider' => 1,
                'is_category' => 1,
                'is_tag' => 1,
                'is_attribute' => 1,
                'is_smtp' => 0,
                'is_talkto' => 0,
                'is_disqus' => 0,
                'is_loader' => 1,
            ];
        }

        // Set Global PageSettings

        try {
            $this->ps = cache()->remember('pagesettings', now()->addDay(), function () {
                return DB::table('pagesettings')->first();
            });
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
