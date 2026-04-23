<?php

namespace App\Http\Controllers\User;

use App\Models\ReferralCode;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ReferralController extends UserBaseController
{
    /**
     * Display the referral program application or status page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user has already applied (has an active code)
        $referralCode = ReferralCode::where('user_id', $user->id)->first();

        if (!$referralCode) {
            return view('user.referral.apply', compact('user'));
        }

        return view('user.affilate.affilate-program', compact('user', 'referralCode'));
    }

    /**
     * Handle the referral program application.
     */
    public function apply(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'referral_name' => 'required|string|max:255',
            'referral_code' => 'required|string|max:50|unique:referral_codes,code|unique:users,affilate_code',
        ];

        $messages = [
            'referral_code.unique' => __('This Referral Code/Name is already taken. Please choose another one.'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        // Sanitize the code (no spaces allowed for coupon functionality)
        $cleanCode = strtoupper(str_replace(' ', '-', trim($request->referral_code)));

        // Double check uniqueness after sanitization
        if (ReferralCode::where('code', $cleanCode)->exists()) {
             return response()->json(['errors' => [__('This Referral Code/Name is already taken after sanitization.')]]);
        }

        // Update User info
        $user->referral_name = $request->referral_name;
        $user->affilate_code = $cleanCode;
        $user->save();

        // Create the Referral Code
        ReferralCode::create([
            'user_id' => $user->id,
            'code' => $cleanCode,
            'owner_role' => 'buyer', // default as per system
            'active' => true,
        ]);

        return response()->json(__('Congratulations! You are now a Referral Ambassador. Your code is active.'));
    }
}
