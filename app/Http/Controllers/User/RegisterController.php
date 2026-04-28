<?php

namespace App\Http\Controllers\User;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Front\FrontBaseController;
use App\Models\Generalsetting;
use App\Models\ManageAgreement;
use App\Models\Notification;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Validator;

class RegisterController extends FrontBaseController
{
    public function showRegisterForm()
    {
        return view('frontend.register');
    }

    public function showRegisterFormWithReferral($referral_name)
    {
        $referrer = User::where('referral_name', $referral_name)->first();
        if ($referrer) {
            Session::put('custom_referral', $referrer->id);
            Session::put('custom_referral_code', $referrer->affilate_code);
        }

        return view('frontend.register');
    }

    public function showVendorRegisterForm()
    {
        // Fetch all agreements (or filter by type if needed)
        $agreements = ManageAgreement::all(); // or ->where('type', 'rider_agreement')->get();

        // dd($agreements);
        // Pass agreements to the view
        return view('frontend.vendor-register', compact('agreements'));
    }

    public function register(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ];

        if ($gs->is_capcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //--- Validation Section Ends

        $user = new User;
        $input = $request->all();
        $input['password'] = bcrypt($request['password']);
        $token = md5(time().$request->name.$request->email);
        $input['verification_link'] = $token;
        $input['affilate_code'] = md5($request->name.$request->email);



        $user->fill($input)->save();


        if ($gs->is_verification_email == 1) {
            $to = $request->email;
            $subject = 'Verify your email address.';
            $msg = 'Dear Customer,<br> We noticed that you need to verify your email address. <a href='.url('user/register/verify/'.$token).'>Simply click here to verify. </a>';
            //Sending Email To Customer
            if ($gs->is_smtp == 1) {
                $data = [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $msg,
                ];

                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $headers = 'From: '.$gs->from_name.'<'.$gs->from_email.'>';
                mail($to, $subject, $msg, $headers);
            }

            return response()->json('We need to verify your email address. We have sent an email to '.$to.' to verify your email address. Please click link in that email to continue.');
        } else {

            $user->email_verified = 'Yes';
            $user->update();
            $notification = new Notification;
            $notification->user_id = $user->id;
            $notification->save();
            Auth::guard('web')->login($user);

            return response()->json(1);
        }

    }

    public function token($token)
    {
        $gs = Generalsetting::findOrFail(1);

        if ($gs->is_verification_email == 1) {
            $user = User::where('verification_link', '=', $token)->first();
            if (isset($user)) {
                $user->email_verified = 'Yes';
                $user->update();
                $notification = new Notification;
                $notification->user_id = $user->id;
                $notification->save();
                Auth::guard('web')->login($user);

                return redirect()->route('user-dashboard')->with('success', 'Email Verified Successfully');
            }
        } else {
            return redirect()->back();
        }
    }
}
