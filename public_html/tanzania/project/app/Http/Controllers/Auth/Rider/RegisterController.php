<?php

namespace App\Http\Controllers\Auth\Rider;

use App\{
	Models\Rider,
	Models\Notification,
	Classes\GeniusMailer,
	Models\Generalsetting,
	Http\Controllers\Controller
};
use Illuminate\Http\Request;
use Auth;
use Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
	{
		$gs = Generalsetting::findOrFail(1);
		if ($gs->is_capcha == 1) {
			$rules = [
				'g-recaptcha-response' => 'required'
			];
			$customs = [
				'g-recaptcha-response.required' => "Please verify that you are not a robot.",
				'g-recaptcha-response.captcha' => "Captcha error! try again later or contact site admin..",
			];
			$validator = Validator::make($request->all(), $rules, $customs);
			if ($validator->fails()) {
				return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
			}
		}
		$rules = [
			'email'   => 'required|email|unique:riders',
			'password' => 'required|confirmed',
			'national_id_front_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'national_id_back_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'license_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'vehicle_type' => 'required|in:bike,truck,car',
		];
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
		}
		$rider = new Rider;
		$input = $request->all();
		if($imageFront = $request->file('national_id_front_image')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/rideridfront', $image_name_front);
            $input['national_id_front_image'] = $image_name_front;
        }
        if($imageBack = $request->file('national_id_back_image')) {
            $image_name_back = \PriceHelper::ImageCreateName($imageBack);
            $imageBack->move('assets/images/rideridback', $image_name_back);
            $input['national_id_back_image'] = $image_name_back;
        }
        if($licenseImage = $request->file('license_image')) {
            $image_name_license = \PriceHelper::ImageCreateName($licenseImage);
            $licenseImage->move('assets/images/riderlicense', $image_name_license);
            $input['license_image'] = $image_name_license;
        }
        if($submerchant_agreement = $request->file('submerchant_agreement')) {
                        $agreement_name_license = \PriceHelper::ImageCreateName($submerchant_agreement);
                        $submerchant_agreement->move('assets/images/submerchantagreementrider', $agreement_name_license);
                        $input['submerchant_agreement'] = $agreement_name_license;
                    }
		$input['password'] = bcrypt($request['password']);
		$token = md5(time() . $request->name . $request->email);
		$input['email_token'] = $token;
		$rider->fill($input)->save();
		if ($gs->is_verification_email == 1) {
			$to = $request->email;
			$subject = 'Verify your email address.';
// 			$msg = "Dear Rider,<br>We noticed that you need to verify your email address.<br>Simply click the link below to verify. <a href=" . url('rider/register/verify/' . $token) . ">" . url('rider/register/verify/' . $token) . "</a>";
            $msg = "
<html>
<body style='font-family:Arial, sans-serif; font-size:14px; color:#333;'>
    <p>Dear {{ $rider->name }},</p>
    <p>Thank you for registering with <strong>Fabilive</strong>.</p>
    <p>Please verify your email address by clicking the link below:</p>
    <p><a href='" . url('rider/register/verify/' . $token) . "' style='color:#1a73e8;'>Verify Email</a></p>
    <br>
    <p>Thanks,<br>Fabilive Team</p>
</body>
</html>
";

			$data = [
				'to' => $to,
				'subject' => $subject,
				'body' => $msg,
			];
			$mailer = new GeniusMailer();
			$mailer->sendCustomMail($data);
			return response()->json('We need to verify your email address. We have sent an email to ' . $to . ' to verify your email address. Please click link in that email to continue.');
		} else {
			$rider->email_verify = 'Yes';
			$rider->update();
			$data = [
				'to' => $rider->email,
				'type' => "new_registration",
				'cname' => $rider->name,
				'oamount' => "",
				'aname' => "",
				'aemail' => "",
				'onumber' => "",
			];
			$mailer = new GeniusMailer();
			$mailer->sendAutoMail($data);
			Auth::guard('rider')->login($rider);
			return response()->json(1);
		}
	}

	public function token($token)
	{
		$gs = Generalsetting::findOrFail(1);

		if ($gs->is_verification_email == 1) {
			$rider = Rider::where('email_token', '=', $token)->first();
			if (isset($rider)) {
				$rider->email_verified = 'Yes';
				$rider->update();

				// Welcome Email For User

				$data = [
					'to' => $rider->email,
					'type' => "new_registration",
					'cname' => $rider->name,
					'oamount' => "",
					'aname' => "",
					'aemail' => "",
					'onumber' => "",
				];
				$mailer = new GeniusMailer();
				$mailer->sendAutoMail($data);


				Auth::gurad('rider')->login($rider);
				return redirect()->route('rider-dashboard')->with('success', __('Email Verified Successfully'));
			}
		} else {
			return redirect()->back();
		}
	}
}
