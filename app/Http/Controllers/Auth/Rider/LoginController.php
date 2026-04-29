<?php

namespace App\Http\Controllers\Auth\Rider;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
    }

    public function login(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);

        if ($gs->is_capcha == 1) {
            $rules = [
                'g-recaptcha-response' => 'required',
            ];
            $customs = [
                'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            ];
            $validator = Validator::make($request->all(), $rules, $customs);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
        }

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        if (Auth::guard('rider')->attempt(['email' => $request->email, 'password' => $request->password])) {
            if (Auth::guard('rider')->user()->ban == 1) {
                Auth::guard('rider')->logout();

                return response()->json(['errors' => [0 => __('Your Account Has Been Banned.')]]);
            }
            if (Auth::guard('rider')->user()->status != 1) {
                Auth::guard('rider')->logout();

                return response()->json(['errors' => [0 => __('Your account is awaiting admin approval.')]]);
            }

            return response()->json(redirect()->intended(route('rider-dashboard'))->getTargetUrl());
        }

        return response()->json(['errors' => [0 => __('Credentials Doesn\'t Match !')]]);
    }

    public function logout()
    {
        Auth::guard('rider')->logout();

        return redirect('/');
    }
}
