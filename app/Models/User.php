<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['name', 'photo', 'zip', 'city_id', 'state_id', 'country','country_id', 'address', 'phone','selfie_image',
     'lat', 'lng',
     'fax', 'email', 'password', 'affilate_code','reff', 'verification_link', 'shop_name', 'owner_name',
     'shop_number', 'shop_address', 'reg_number', 'shop_message','business_registration_certificate','taxpayer_card_copy',
     'id_card_copy','passport_copy','driver_license_copy','residence_permit', 'is_vendor', 'shop_details',
     'shop_image', 'shipping_cost', 'date', 'mail_sent', 'email_verified','current_balance', 'email_token', 'reward',
      'national_id_front_image', 'national_id_back_image','submerchant_agreement', 'license_image', 'is_verified',
      'vendor_status', 'vendor_rejection_reason', 'vendor_approved_at'];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function withdraws()
    {
        return $this->hasMany('App\Models\Withdraw');
    }

    public function vendororders()
    {
        return $this->hasMany('App\Models\VendorOrder', 'user_id');
    }

    public function shippings()
    {
        return $this->hasMany('App\Models\Shipping', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function services()
    {
        return $this->hasMany('App\Models\Service');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function IsVendor()
    {
        if ($this->is_vendor == 2) {
            return true;
        }
        return false;
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Reply');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function socialProviders()
    {
        return $this->hasMany('App\Models\SocialProvider');
    }

    public function conversations()
    {
        return $this->hasMany('App\Models\AdminUserConversation');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    public function deposits()
    {
        return $this->hasMany('App\Models\Deposit', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'user_id');
    }

    public function walletLedgers()
    {
        return $this->hasMany('App\Models\WalletLedger', 'user_id');
    }

    public function senders()
    {
        return $this->hasMany('App\Models\Conversation', 'sent_user');
    }

    public function recievers()
    {
        return $this->hasMany('App\Models\Conversation', 'recieved_user');
    }

    public function notivications()
    {
        return $this->hasMany('App\Models\UserNotification', 'user_id');
    }

    public function subscribes()
    {
        return $this->hasMany('App\Models\UserSubscription');
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\FavoriteSeller');
    }

    public function packages()
    {
        return $this->hasMany('App\Models\Package', 'user_id');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Report', 'user_id');
    }

    public function verifies()
    {
        return $this->hasMany('App\Models\Verification', 'user_id');
    }

    public function sociallinks()
    {
        return $this->hasMany('App\Models\SocialLink', 'user_id');
    }

    public function wishlistCount()
    {
        return \App\Models\Wishlist::where('user_id', '=', $this->id)->with(['product'])->whereHas('product', function ($query) {
            $query->where('status', '=', 1);
        })->count();
    }

    public function checkVerification()
    {
        return count($this->verifies) > 0 ?
            (empty($this->verifies()->where('admin_warning', '=', '0')->latest('id')->first()->status) ? false : ($this->verifies()->latest('id')->first()->status == 'Pending' ? true : false)) : false;
    }

    public function checkStatus()
    {
        return count($this->verifies) > 0 ? ($this->verifies()->latest('id')->first()->status == 'Verified' ? true : false) : false;
    }

    public function checkWarning()
    {
        return count($this->verifies) > 0 ? (empty($this->verifies()->where('admin_warning', '=', '1')->latest('id')->first()) ? false : (empty($this->verifies()->where('admin_warning', '=', '1')->latest('id')->first()->status) ? true : false)) : false;
    }

    public function displayWarning()
    {
        return $this->verifies()->where('admin_warning', '=', '1')->latest('id')->first()->warning_reason;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
