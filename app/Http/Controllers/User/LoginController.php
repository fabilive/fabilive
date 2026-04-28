<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Front\FrontBaseController;
use Auth;
use Illuminate\Http\Request;
use Validator;

class LoginController extends FrontBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
    }

    public function showLoginForm()
    {

        return view('frontend.login');
    }

    public function status($status)
    {
        return view('user.success', compact('status'));
    }

    public function showVendorLoginForm()
    {

        return view('frontend.vendor-login');
    }

    public function login(Request $request)
    {
        //--- Validation Section
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $gs = \App\Models\Generalsetting::findOrFail(1);
        if ($gs->is_capcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //--- Validation Section Ends

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // if successful, then redirect to their intended location

            // Check If Email is verified or not
            if (Auth::guard('web')->user()->email_verified == 'No') {
                Auth::guard('web')->logout();

                return response()->json(['errors' => [0 => 'Your Email is not Verified!']]);
            }

            if (Auth::guard('web')->user()->ban == 1) {
                Auth::guard('web')->logout();

                return response()->json(['errors' => [0 => 'Your Account Has Been Banned.']]);
            }

            // Login as User
            return response()->json(route('user-dashboard'));
        }

        // if unsuccessful, then redirect back to the login with the form data
        return response()->json(['errors' => [0 => 'Credentials Doesn\'t Match !']]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
