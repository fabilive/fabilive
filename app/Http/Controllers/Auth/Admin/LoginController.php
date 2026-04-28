<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function showForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        //--- Validation Section
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        // Check if reCAPTCHA is enabled
        $gs = Generalsetting::findOrFail(1);
        if ($gs->is_capcha == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //--- Validation Section Ends

        // Attempt to log the user in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Generate OTP and send email
            $admin = Auth::guard('admin')->user();
            $code = str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);

            $admin->update([
                'otp_code' => $code,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            $subject = 'Admin Login OTP Verification';
            $msg = "Hello {$admin->name},<br><br>Your OTP code for admin login is: <b>{$code}</b><br><br>This code will expire in 10 minutes.";

            if ($gs->is_smtp == 1) {
                $data = [
                    'to' => $admin->email,
                    'subject' => $subject,
                    'body' => $msg,
                ];
                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $headers = 'From: ' . $gs->from_name . '<' . $gs->from_email . ">\r\n";
                $headers .= "Content-type: text/html\r\n";
                mail($admin->email, $subject, $msg, $headers);
            }

            // Return a route to redirect to OTP verify page
            return response()->json(route('admin.otp.show'));
        }

        // if unsuccessful, then redirect back to the login with the form data
        return response()->json(['errors' => [0 => 'Credentials Doesn\'t Match !']]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
