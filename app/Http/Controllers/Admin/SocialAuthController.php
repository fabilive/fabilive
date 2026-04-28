<?php

namespace App\Http\Controllers\Admin;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Generalsetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->withErrors(['Unable to authenticate with Google.']);
        }

        // Only allow existing admin accounts (no self-registration)
        $admin = Admin::where('email', $googleUser->getEmail())->first();

        if (!$admin) {
            return redirect()->route('admin.login')->withErrors(['No administrator account found with this email.']);
        }

        // Update google id if not set
        if (!$admin->google_id) {
            $admin->update(['google_id' => $googleUser->getId()]);
        }

        // Log the user in
        Auth::guard('admin')->login($admin, true);

        // Generate and send OTP
        $this->sendOtp($admin);

        return redirect()->route('admin.otp.show');
    }

    protected function sendOtp(Admin $admin)
    {
        $code = str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        $admin->update([
            'otp_code' => $code,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $gs = Generalsetting::findOrFail(1);
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
    }
}
