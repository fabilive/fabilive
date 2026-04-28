<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\SocialProvider;
use App\Models\Socialsetting;
use App\Models\User;
use Auth;
use Config;
use Illuminate\Http\Request;
use Session;
use Socialite;

class SocialRegisterController extends Controller
{
    public function __construct()
    {
        $link = Socialsetting::findOrFail(1);
        Config::set('services.google.client_id', $link->gclient_id);
        Config::set('services.google.client_secret', $link->gclient_secret);
        Config::set('services.google.redirect', url('/auth/google/callback'));
        Config::set('services.facebook.client_id', $link->fclient_id);
        Config::set('services.facebook.client_secret', $link->fclient_secret);
        $url = url('/auth/facebook/callback');
        $url = preg_replace('/^http:/i', 'https:', $url);
        Config::set('services.facebook.redirect', $url);
    }

    public function redirectToProvider(Request $request, $provider)
    {
        if ($request->has('role')) {
            Session::put('social_role', $request->role);
        }
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/');
        }

        $role = Session::get('social_role', 'buyer');
        Session::forget('social_role');

        $socialProvider = SocialProvider::where('provider_id', $socialUser->getId())->first();
        if (! $socialProvider) {
            $user = User::where('email', '=', $socialUser->email)->first();
            
            if (!$user) {
                // Create new user
                $user = new User;
                $user->email = $socialUser->email;
                $user->name = $socialUser->name;
                $user->photo = $socialUser->avatar_original;
                $user->email_verified = 'Yes';
                $user->is_provider = 1;
                $user->affilate_code = md5($socialUser->name.$socialUser->email);
                
                // Set role
                if ($role === 'seller') {
                    $user->is_vendor = 2; // Approved vendor status
                    $user->shop_name = $socialUser->name . "'s Shop";
                } else {
                    $user->is_vendor = 0;
                }
                
                $user->save();
                
                $user->socialProviders()->create(
                    ['provider_id' => $socialUser->getId(), 'provider' => $provider]
                );
                
                $notification = new Notification;
                $notification->user_id = $user->id;
                $notification->save();
            } else {
                // Link existing user if provider doesn't exist
                $user->socialProviders()->create(
                    ['provider_id' => $socialUser->getId(), 'provider' => $provider]
                );
            }
        } else {
            $user = $socialProvider->user;
        }

        Auth::login($user);

        if ($user->is_vendor == 2) {
            return redirect()->route('vendor.dashboard');
        }

        return redirect()->route('user-dashboard');
    }

}
