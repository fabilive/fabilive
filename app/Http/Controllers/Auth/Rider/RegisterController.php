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
		if ($gs->is_capcha == 1 && config('app.env') !== 'local') {
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
            'password'=> 'required|confirmed',
            'rider_type' => 'required|in:company,individual',
            'national_id_front_image' => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'national_id_back_image'  => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'license_image'           => 'required|image|mimes:jpg,jpeg,png|max:10240',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            \Log::error('Validation Failed 1: ', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()]);
        }
        if ($request->rider_type === 'company') {
            $request->validate([
                'company_registration_document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
                'id_company_owner' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
                'live_selfie_company' => 'required|file|max:10240',
                'transport_license' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
                'insurance_certificate_company' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
                'tin_company' => 'required|string',
            ]);
        } else {
            $v2 = Validator::make($request->all(), [
                'vehicle_type_individual' => 'required|string',
                'tin_individual' => 'required|string',
                'driver_license_individual' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
                'live_selfie_individual' => 'required|file|max:10240',
                'vehicle_registration_certificate' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
                'insurance_certificate_individual' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
                'criminal_records' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
            ]);
            if ($v2->fails()) {
                \Log::error('Validation Failed 2 (Individual): ', $v2->errors()->toArray());
                return response()->json(['errors' => $v2->errors()]);
            }
        }
		$rider = new Rider;
		$input = $request->all();
		if($imageFront = $request->file('live_selfie_company')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/liveselfiecompany', $image_name_front);
            $input['live_selfie_company'] = $image_name_front;
        }
        if($imageFront = $request->file('live_selfie_individual')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/liveselfieindividual', $image_name_front);
            $input['live_selfie_individual'] = $image_name_front;
        }
		if($imageFront = $request->file('company_registration_document')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/companyregistrationdocument', $image_name_front);
            $input['company_registration_document'] = $image_name_front;
        }
        if($imageFront = $request->file('id_company_owner')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/companyownerid', $image_name_front);
            $input['id_company_owner'] = $image_name_front;
        }
        if($imageFront = $request->file('transport_license')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/transportlicense', $image_name_front);
            $input['transport_license'] = $image_name_front;
        }
        if($imageFront = $request->file('insurance_certificate_company')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/insurancecertificatecompany', $image_name_front);
            $input['insurance_certificate_company'] = $image_name_front;
        }
        if($imageFront = $request->file('driver_license_individual')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/driverlicenseindividual', $image_name_front);
            $input['driver_license_individual'] = $image_name_front;
        }
        if($imageFront = $request->file('vehicle_registration_certificate')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/vehicleregistrationcertificate', $image_name_front);
            $input['vehicle_registration_certificate'] = $image_name_front;
        }
        if($imageFront = $request->file('insurance_certificate_individual')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/insurancecertificateindividual', $image_name_front);
            $input['insurance_certificate_individual'] = $image_name_front;
        }
        if($imageFront = $request->file('criminal_records')) {
            $image_name_front = \PriceHelper::ImageCreateName($imageFront);
            $imageFront->move('assets/images/criminalrecords', $image_name_front);
            $input['criminal_records'] = $image_name_front;
        }
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
        $input['rider_status'] = 'pending';
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
// 			Auth::guard('rider')->login($rider);
        //     return redirect('/')
        // ->with('success',
        //     'Your request has been sent to admin. Please wait for approval.');
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
