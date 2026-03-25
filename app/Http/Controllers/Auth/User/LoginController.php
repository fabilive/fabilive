<?php
namespace App\Http\Controllers\Auth\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{
  public function __construct()
  {
    $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
  }
  public function login(Request $request)
  {
    $rules = [
      'email'   => 'required|email',
      'password' => 'required',
    ];

    $gs = \App\Models\Generalsetting::findOrFail(1);
    if ($gs->is_capcha == 1 && config('app.env') !== 'local') {
        $rules['g-recaptcha-response'] = 'required|captcha';
    }
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    }
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
      if (Auth::guard('web')->user()->email_verified == 'No') {
        Auth::guard('web')->logout();
        return response()->json(array('errors' => [0 => __('Your Email is not Verified!')]));
      }
      if (Auth::guard('web')->user()->ban == 1) {
        Auth::guard('web')->logout();
        return response()->json(array('errors' => [0 => __('Your Account Has Been Banned.')]));
      }
      if (empty($request->auth_modal)) {
        if (!empty($request->modal)) {
          if (!empty($request->vendor)) {
            if (Auth::guard('web')->user()->is_vendor == 2) {
              return response()->json(route('vendor.dashboard'));
            } else {
              return response()->json(route('user-vendor-request', 8));
            }
          }
          return response()->json(1);
        }
      }
      return response()->json(redirect()->intended(route('user-dashboard'))->getTargetUrl());
    }
    return response()->json(array('errors' => [0 => __('Credentials Doesn\'t Match !')]));
  }

  public function logout()
  {
    Auth::logout();
    return redirect('/');
  }
}
