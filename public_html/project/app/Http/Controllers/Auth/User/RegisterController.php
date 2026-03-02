<?php

namespace App\Http\Controllers\Auth\User;

use App\{
    Models\User,
    Models\Notification,
    Classes\GeniusMailer,
    Models\Generalsetting,
    Http\Controllers\Controller
};
use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
        // dd($request->all());
        $gs = Generalsetting::findOrFail(1);

        // ------------------- CAPTCHA Validation -------------------
        if ($gs->is_capcha == 1) {
            $rules = ['g-recaptcha-response' => 'required'];
            $customs = [
                'g-recaptcha-response.required' => "Please verify that you are not a robot.",
                'g-recaptcha-response.captcha' => "Captcha error! try again later or contact site admin..",
            ];
            $validator = Validator::make($request->all(), $rules, $customs);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
        }

        // ------------------- Check if user exists -------------------
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            // Prevent duplicate vendor submission
            if ($existingUser->is_vendor == 1) {
                return response()->json([
                    'errors' => ['email' => __('You have already submitted a vendor request. Please wait for approval.')]
                ]);
            }

            if ($existingUser->is_vendor == 2) {
                return response()->json([
                    'errors' => ['email' => __('You are already an approved vendor.')]
                ]);
            }

            $user = $existingUser;

            // ------------------- Upgrade to Vendor -------------------
            if (!empty($request->vendor)) {
                $validator = Validator::make($request->all(), [
                    'shop_name' => 'unique:users,shop_name,' . $user->id,
                    'reg_number' => 'required',
                ], [
                    'shop_name.unique' => __('This Shop Name has already been taken.'),
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                }

                $user->is_vendor = 1;
                $user->shop_name = $request->shop_name;
                $user->reg_number = $request->reg_number;
                $user->owner_name = $request->owner_name;
                $user->shop_message = $request->shop_message;

                $this->handleVendorUploads($request, $user);
                $user->save();
            }
        } else {
            // ------------------- New User Registration -------------------
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }

            $user = new User();
            $input = $request->all();
            $input['password'] = bcrypt($request['password']);
            $token = md5(time() . $request->name . $request->email);
            $input['verification_link'] = $token;
            $input['affilate_code'] = md5($request->name . $request->email);

            // Handle affiliate bonus
            if (Session::has('affilate')) {
                $affiliateId = Session::get('affilate');
                $referrer = User::find($affiliateId);
                $general = Generalsetting::first();


                // dd($general);
                $affiliateBonus = $general->referral_bonus ?? 0;
                $referrerBonus = $general->referral_amount ?? 0;
                $input['ref_user_id'] = $affiliateId;
                $input['balance'] = $affiliateBonus;
                if ($referrer) {
                    $referrer->balance += $referrerBonus;
                    $referrer->affilate_income += $referrerBonus; // Track referral earnings

                    $referrer->save();
                }
            }

            // Vendor registration during new signup
            // Vendor registration during new signup
            if (!empty($request->vendor)) {

                // Validate shop fields + selfie
                $validator = Validator::make($request->all(), [
                    'shop_name' => 'unique:users,' . ($user->id ?? 'NULL'),
                    'reg_number' => 'required',
                    'shop_number' => 'max:10',
                    'selfie_image' => 'required|file|mimes:jpg,jpeg,png', // <-- Added
                ], [
                    'shop_name.unique' => __('This Shop Name has already been taken.'),
                    'shop_number.max' => __('Shop Number Must Be Less Than 10 Digits.'),
                    'selfie_image.required' => __('Please capture and upload a selfie image.'),
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                }

                // Custom validation: at least one ID document is required
                if (
                    !$request->hasFile('national_id_front_image') &&
                    !$request->hasFile('national_id_back_image')
                ) {
                    return response()->json([
                        'errors' => ['files' => __('Please upload National ID Front/Back')]
                    ]);
                }

                $input['is_vendor'] = 1;
            }



            $user->fill($input)->save();

            // Handle vendor uploads if vendor
            if (!empty($request->vendor)) {
                $this->handleVendorUploads($request, $user);
                $user->save();
            }
        }

        // ------------------- Email Verification / Auto Login -------------------
        if ($gs->is_verification_email == 1 && !$existingUser) {
            $to = $user->email;
            $subject = 'Verify your email address.';
            $msg = "Dear Customer,<br>Please click the link below to verify your email address: <a href=" . url('user/register/verify/' . $user->verification_link) . ">" . url('user/register/verify/' . $user->verification_link) . "</a>";
            $data = ['to' => $to, 'subject' => $subject, 'body' => $msg];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);

            return response()->json('We need to verify your email address. Please check your email.');
        } else {
            $user->email_verified = 'Yes';
            $user->update();

            // Notification
            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->save();

            $data = [
                'to' => $user->email,
                'type' => "new_registration",
                'cname' => $user->name,
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'onumber' => "",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendAutoMail($data);

            Auth::login($user);
            echo 1;
        }
    }

    /**
     * Handle vendor file uploads (DRY)
     */
    /**
     * Handle vendor file uploads (DRY) including selfie
     */
    private function handleVendorUploads($request, $user)
    {
        $files = [
            'selfie_image' => 'vendorselfie',                 // <-- Added selfie
            'national_id_front_image' => 'vendorfront',
            'national_id_back_image' => 'vendorback',
            'license_image' => 'vendorlicense',
            'submerchant_agreement' => 'submerchantagreement'
        ];

        foreach ($files as $field => $folder) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = \PriceHelper::ImageCreateName($file);
                $file->move("assets/images/{$folder}", $filename);
                $user->{$field} = $filename; // Saves the filename in DB
            }
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

                // Welcome Email For User

                $data = [
                    'to' => $user->email,
                    'type' => "new_registration",
                    'cname' => $user->name,
                    'oamount' => "",
                    'aname' => "",
                    'aemail' => "",
                    'onumber' => "",
                ];
                $mailer = new GeniusMailer();
                $mailer->sendAutoMail($data);


                Auth::login($user);
                return redirect()->route('user-dashboard')->with('success', __('Email Verified Successfully'));
            }
        } else {
            return redirect()->back();
        }
    }
}
