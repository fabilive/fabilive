<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AdminBaseController extends Controller
{
    protected $gs;

    protected $curr;

    protected $language;

    protected $language_id;

    public function __construct()
    {
        $this->middleware('auth:admin');

        // Test database connectivity
        $dbAvailable = false;
        try {
            \DB::connection()->getPdo();
            $dbAvailable = true;
        } catch (\Exception $e) {
            \Log::warning('AdminBaseController: Database unreachable — using in-memory defaults.');
        }

        // 1. General Settings Fail-safe
        $this->gs = null;
        if ($dbAvailable) {
            try {
                $this->gs = \DB::table('generalsettings')->find(1);
            } catch (\Exception $e) {}
        }

        if (!$this->gs) {
            $this->gs = new \stdClass();
            $this->gs->title = "Fabilive";
            $this->gs->logo = "logo.png";
            $this->gs->is_admin_loader = 0;
            $this->gs->fixed_commission = 0;
            $this->gs->percentage_commission = 0;
            $this->gs->currency_format = 0;
        }

        // 2. Language Fail-safe
        $this->language = null;
        if ($dbAvailable) {
            try {
                $this->language = \DB::table('admin_languages')->where('is_default', '=', 1)->first();
                if (!$this->language) {
                    $this->language = \DB::table('admin_languages')->first();
                }
            } catch (\Exception $e) {}
        }

        if (!$this->language) {
            $this->language = new \stdClass();
            $this->language->id = 1;
            $this->language->name = "English";
            $this->language->is_default = 1;
        }

        view()->share('langg', $this->language);
        if ($this->language && isset($this->language->name)) {
            App::setlocale($this->language->name);
        }

        // 3. Currency Fail-safe
        $this->curr = null;
        if ($dbAvailable) {
            try {
                $this->curr = \DB::table('currencies')->where('is_default', '=', 1)->first();
                if (!$this->curr) {
                    $this->curr = \DB::table('currencies')->first();
                }
            } catch (\Exception $e) {}
        }

        if (!$this->curr) {
            $this->curr = new \stdClass();
            $this->curr->id = 1;
            $this->curr->name = "CFA";
            $this->curr->sign = "CFA";
            $this->curr->value = 1;
        }

        view()->share('gs', $this->gs);
        view()->share('curr', $this->curr);
        view()->share('admin_lang', $this->language);
    }
}
