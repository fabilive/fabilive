<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable implements FilamentUser
{
    use HasFactory;

    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'email_token', 'role_id', 'photo', 'section', 'created_at', 'updated_at', 'remember_token', 'shop_name', 'google_id', 'otp_code', 'otp_expires_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo('App\Models\Role')->withDefault();
    }

    public function IsSuper()
    {
        if ($this->id == 1 || $this->email == 'hello@fabilive.com') {
            return true;
        }

        return false;
    }

    public function sectionCheck($value)
    {
        $sections = [];
        if (! empty($this->section)) {
            $sections = explode(' , ', $this->section);
        } else {
            if (isset($this->role->section) && ! empty($this->role->section)) {
                $sections = explode(' , ', $this->role->section);
            }
        }

        if (in_array($value, $sections)) {
            return true;
        } else {
            return false;
        }
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
