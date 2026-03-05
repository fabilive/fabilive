<?php
namespace App\Http\Controllers\Auth\Rider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
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
      'password' => 'required'
    ];
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    }
    if (Auth::guard('rider')->attempt(['email' => $request->email, 'password' => $request->password])) {
      if (Auth::guard('rider')->user()->email_verified == 'No') {
        Auth::guard('rider')->logout();
        return response()->json(array('errors' => [0 => __('Your Email is not Verified!')]));
      }
      if (Auth::guard('rider')->user()->ban == 1) {
        Auth::guard('rider')->logout();
        return response()->json(array('errors' => [0 => __('Your Account Has Been Banned.')]));
      }
            if (Auth::guard('rider')->user()->rider_status !== 'accepted') {
                Auth::guard('rider')->logout();
                return response()->json(['errors' => [0 => __('Your account is awaiting admin approval.')]]);
            }
      return response()->json(redirect()->intended(route('rider-dashboard'))->getTargetUrl());
    }
    return response()->json(array('errors' => [0 => __('Credentials Doesn\'t Match !')]));
  }
  public function logout()
  {
    Auth::guard('rider')->logout();
    return redirect('/');
  }
}
