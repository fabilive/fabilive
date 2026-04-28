<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function showForm(Request $request)
    {
        if ($request->session()->get('admin_2fa_verified')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!$admin->otp_code || !$admin->otp_expires_at) {
            return redirect()->route('admin.login')->withErrors(['Please login again to request a new code.']);
        }

        if (now()->greaterThan($admin->otp_expires_at)) {
            return back()->withErrors(['The OTP code has expired. Please login again.']);
        }

        if ($request->otp_code !== $admin->otp_code) {
            return back()->withErrors(['Invalid OTP code.']);
        }

        // OTP is valid
        $admin->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        $request->session()->put('admin_2fa_verified', true);

        return redirect()->route('admin.dashboard');
    }
}
