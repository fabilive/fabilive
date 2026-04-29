<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socialsetting extends Model
{
    protected $fillable = ['facebook', 'twitter', 'gplus', 'linkedin', 'dribble', 'f_status', 't_status', 'g_status', 'l_status', 'd_status', 'f_check', 'g_check', 'fclient_id', 'fclient_secret', 'fredirect', 'gclient_id', 'gclient_secret', 'gredirect'];

    public $timestamps = false;

    /**
     * Get the first social setting record with a robust fail-safe.
     * 
     * @return object|\stdClass
     */
    public static function safeFirst()
    {
        try {
            $social = cache()->remember('socialsettings', now()->addDay(), function () {
                \DB::connection()->getPdo();
                return \DB::table('socialsettings')->first();
            });

            if ($social) return $social;
        } catch (\Exception $e) {}

        $social = new \stdClass();
        $social->facebook = '#';
        $social->twitter = '#';
        $social->gplus = '#';
        $social->linkedin = '#';
        $social->f_status = 0;
        $social->t_status = 0;
        $social->g_status = 0;
        $social->l_status = 0;
        $social->g_check = 0;
        $social->f_check = 0;
        return $social;
    }
}
