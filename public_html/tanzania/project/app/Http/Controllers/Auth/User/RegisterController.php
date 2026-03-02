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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

     public function register(Request $request)
    {
    	$gs = Generalsetting::findOrFail(1);
    	if($gs->is_capcha == 1)
        {
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
		        'email'   => 'required|email|unique:users',
		        'password' => 'required|confirmed',
                ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
	        $user = new User;
	        $input = $request->all();
	        $input['password'] = bcrypt($request['password']);
	        $token = md5(time().$request->name.$request->email);
	        $input['verification_link'] = $token;
	        $input['affilate_code'] = md5($request->name.$request->email);
            if (Session::has('affilate')) {
            $affiliateId = Session::get('affilate');
            $referrer = User::find($affiliateId);
            $general = \App\Models\Generalsetting::first();
            $affiliateBonus = $general->referral_bonus ?? 0; // for the new user
            $referrerBonus = $general->referral_amount ?? 0; // for the referring user
            $user->ref_user_id = $affiliateId;
            $user->balance = $affiliateBonus;
            if ($referrer) {
                $referrer->balance += $referrerBonus;
                $referrer->save();
            }
        }
	          if(!empty($request->vendor))
	          {
					$rules = [
						'shop_name' => 'unique:users',
						'shop_number'  => 'max:10',
						'reg_number' => 'required',
							];
					$customs = [
						'shop_name.unique' => __('This Shop Name has already been taken.'),
						'shop_number.max'  => __('Shop Number Must Be Less Then 10 Digit.')
					];
					$validator = Validator::make($request->all(), $rules, $customs);
					if ($validator->fails()) {
					return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
					}
					$input['is_vendor'] = 1;
					if($imageFront = $request->file('national_id_front_image')) {
                        $image_name_front = \PriceHelper::ImageCreateName($imageFront);
                        $imageFront->move('assets/images/vendorfront', $image_name_front);
                        $input['national_id_front_image'] = $image_name_front;
                    }
                    if($imageBack = $request->file('national_id_back_image')) {
                        $image_name_back = \PriceHelper::ImageCreateName($imageBack);
                        $imageBack->move('assets/images/vendorback', $image_name_back);
                        $input['national_id_back_image'] = $image_name_back;
                    }
                    if($licenseImage = $request->file('license_image')) {
                        $image_name_license = \PriceHelper::ImageCreateName($licenseImage);
                        $licenseImage->move('assets/images/vendorlicense', $image_name_license);
                        $input['license_image'] = $image_name_license;
                    }
                    if($submerchant_agreement = $request->file('submerchant_agreement')) {
                        $agreement_name_license = \PriceHelper::ImageCreateName($submerchant_agreement);
                        $submerchant_agreement->move('assets/images/submerchantagreement', $agreement_name_license);
                        $input['submerchant_agreement'] = $agreement_name_license;
                    }
                    // below 6 new fields are added
                    if($businessReg = $request->file('business_registration_certificate')) {
                        $name = \PriceHelper::ImageCreateName($businessReg);
                        $businessReg->move('assets/images/business_registration', $name);
                        $input['business_registration_certificate'] = $name;
                    }
                    if($tin = $request->file('tin')) {
                        $name = \PriceHelper::ImageCreateName($tin);
                        $tin->move('assets/images/tin', $name);
                        $input['tin'] = $name;
                    }
                    if($idCard = $request->file('id_card_copy')) {
                        $name = \PriceHelper::ImageCreateName($idCard);
                        $idCard->move('assets/images/idcards', $name);
                        $input['id_card_copy'] = $name;
                    }
                    if($passport = $request->file('passport_copy')) {
                        $name = \PriceHelper::ImageCreateName($passport);
                        $passport->move('assets/images/passports', $name);
                        $input['passport_copy'] = $name;
                    }
                    if($driverLicense = $request->file('driver_license_copy')) {
                        $name = \PriceHelper::ImageCreateName($driverLicense);
                        $driverLicense->move('assets/images/drivers', $name);
                        $input['driver_license_copy'] = $name;
                    }
                    if($residencePermit = $request->file('residence_permit')) {
                        $name = \PriceHelper::ImageCreateName($residencePermit);
                        $residencePermit->move('assets/images/residence', $name);
                        $input['residence_permit'] = $name;
                    }
			  }
			$user->fill($input)->save();
	        if($gs->is_verification_email == 1)
	        {
	        $to = $request->email;
	        $subject = 'Verify your email address.';
	        $msg = "Dear Customer,<br>We noticed that you need to verify your email address.<br>Simply click the link below to verify. <a href=".url('user/register/verify/'.$token).">".url('user/register/verify/'.$token)."</a>";
	        $data = [
	            'to' => $to,
	            'subject' => $subject,
	            'body' => $msg,
	        ];
	        $mailer = new GeniusMailer();
	        $mailer->sendCustomMail($data);
          	return response()->json('We need to verify your email address. We have sent an email to '.$to.' to verify your email address. Please click link in that email to continue.');
	        }
	        else {
            $user->email_verified = 'Yes';
            $user->update();
	        $notification = new Notification;
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

//     public function register(Request $request)
//     {
//     	$gs = Generalsetting::findOrFail(1);
//     	if($gs->is_capcha == 1)
//         {
//             $rules = [
//                 'g-recaptcha-response' => 'required'
//             ];
//             $customs = [
//                 'g-recaptcha-response.required' => "Please verify that you are not a robot.",
//                 'g-recaptcha-response.captcha' => "Captcha error! try again later or contact site admin..",
//             ];
//             $validator = Validator::make($request->all(), $rules, $customs);
//             if ($validator->fails()) {
//               return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
//             }
//         }
//         $rules = [
// 		        'email'   => 'required|email|unique:users',
// 		        'password' => 'required|confirmed',
//                 ];
//         $validator = Validator::make($request->all(), $rules);
//         if ($validator->fails()) {
//           return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
//         }
// 	        $user = new User;
// 	        $input = $request->all();        
// 	        $input['password'] = bcrypt($request['password']);
// 	        $token = md5(time().$request->name.$request->email);
// 	        $input['verification_link'] = $token;
// 	        $input['affilate_code'] = md5($request->name.$request->email);
	        
	        
// 	        if (Session::has('affilate')) {
//             $affiliateId = Session::get('affilate');
//             $referrer = User::find($affiliateId);
//             $general = \App\Models\Generalsetting::first();
//             $affiliateBonus = $general->referral_bonus ?? 0; // for the new user
//             $referrerBonus = $general->referral_amount ?? 0; // for the referring user
//             $user->ref_user_id = $affiliateId;
//             $user->balance = $affiliateBonus;
//             if ($referrer) {
//                 $referrer->balance += $referrerBonus;
//                 $referrer->save();
//             }
//         }
// 	          if(!empty($request->vendor))
// 	          {
// 					$rules = [
// 						'shop_name' => 'unique:users',
// 						'shop_number'  => 'max:10',
// 						'reg_number' => 'required',
// 							];
// 					$customs = [
// 						'shop_name.unique' => __('This Shop Name has already been taken.'),
// 						'shop_number.max'  => __('Shop Number Must Be Less Then 10 Digit.')
// 					];
// 					$validator = Validator::make($request->all(), $rules, $customs);
// 					if ($validator->fails()) {
// 					return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
// 					}
// 					$input['is_vendor'] = 1;
// 					if($imageFront = $request->file('national_id_front_image')) {
//                         $image_name_front = \PriceHelper::ImageCreateName($imageFront);
//                         $imageFront->move('assets/images/vendorfront', $image_name_front);
//                         $input['national_id_front_image'] = $image_name_front;
//                     }
//                     if($imageBack = $request->file('national_id_back_image')) {
//                         $image_name_back = \PriceHelper::ImageCreateName($imageBack);
//                         $imageBack->move('assets/images/vendorback', $image_name_back);
//                         $input['national_id_back_image'] = $image_name_back;
//                     }
//                     if($licenseImage = $request->file('license_image')) {
//                         $image_name_license = \PriceHelper::ImageCreateName($licenseImage);
//                         $licenseImage->move('assets/images/vendorlicense', $image_name_license);
//                         $input['license_image'] = $image_name_license;
//                     }
//                     if($submerchant_agreement = $request->file('submerchant_agreement')) {
//                         $agreement_name_license = \PriceHelper::ImageCreateName($submerchant_agreement);
//                         $submerchant_agreement->move('assets/images/submerchantagreement', $agreement_name_license);
//                         $input['submerchant_agreement'] = $agreement_name_license;
//                     }
// 			  }
// 			$user->fill($input)->save();
// 	        if($gs->is_verification_email == 1)
// 	        {
// 	        $to = $request->email;
// 	        $subject = 'Verify your email address.';
// 	        $msg = "Dear Customer,<br>We noticed that you need to verify your email address.<br>Simply click the link below to verify. <a href=".url('user/register/verify/'.$token).">".url('user/register/verify/'.$token)."</a>";
// 	        //Sending Email To Customer
// 	        $data = [
// 	            'to' => $to,
// 	            'subject' => $subject,
// 	            'body' => $msg,
// 	        ];
// 	        $mailer = new GeniusMailer();
// 	        $mailer->sendCustomMail($data);
//           	return response()->json('We need to verify your email address. We have sent an email to '.$to.' to verify your email address. Please click link in that email to continue.');
// 	        }
// 	        else {
//             $user->email_verified = 'Yes';
//             $user->update();
// 	        $notification = new Notification;
// 	        $notification->user_id = $user->id;
// 			$notification->save();
// 			$data = [
// 				'to' => $user->email,
// 				'type' => "new_registration",
// 				'cname' => $user->name,
// 				'oamount' => "",
// 				'aname' => "",
// 				'aemail' => "",
// 				'onumber' => "",
// 			];
// 			$mailer = new GeniusMailer();
// 			$mailer->sendAutoMail($data);    
//             Auth::login($user); 
//           	return response()->json(1);
// 	        }

//     }

//         public function register(Request $request)
//         {
//     	$gs = Generalsetting::findOrFail(1);
//     	if($gs->is_capcha == 1)
//         {
//             $rules = [
//                 'g-recaptcha-response' => 'required'
//             ];
//             $customs = [
//                 'g-recaptcha-response.required' => "Please verify that you are not a robot.",
//                 'g-recaptcha-response.captcha' => "Captcha error! try again later or contact site admin..",
//             ];
//             $validator = Validator::make($request->all(), $rules, $customs);
//             if ($validator->fails()) {
//               return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
//             }
//         }
//         $rules = [
// 		        'email'   => 'required|email|unique:users',
// 		        'password' => 'required|confirmed',
//                 ];
//         $validator = Validator::make($request->all(), $rules);
//         if ($validator->fails()) {
//           return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
//         }
// 	        $user = new User;
// 	        $input = $request->all();
// 	        $input['password'] = bcrypt($request['password']);
// 	        $token = md5(time().$request->name.$request->email);
// 	        $input['verification_link'] = $token;
// 	        $input['affilate_code'] = md5($request->name.$request->email);
	        
//             if (Session::has('affilate')) {
//             $affiliateId = Session::get('affilate');
//             $referrer = User::find($affiliateId);
//             $general = \App\Models\Generalsetting::first();
//             $affiliateBonus = $general->referral_bonus ?? 0; // for the new user
//             $referrerBonus = $general->referral_amount ?? 0; // for the referring user
//             $user->ref_user_id = $affiliateId;
//             $user->balance = $affiliateBonus;
//             if ($referrer) {
//                 $referrer->balance += $referrerBonus;
//                 $referrer->save();
//             }
//         }

// 	          if(!empty($request->vendor))
// 	          {
// 					$rules = [
// 						'shop_name' => 'unique:users',
// 						'shop_number'  => 'max:10',
// 						'reg_number' => 'required',
// 							];
// 					$customs = [
// 						'shop_name.unique' => __('This Shop Name has already been taken.'),
// 						'shop_number.max'  => __('Shop Number Must Be Less Then 10 Digit.')
// 					];
// 					$validator = Validator::make($request->all(), $rules, $customs);
// 					if ($validator->fails()) {
// 					return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
// 					}
// 					$input['is_vendor'] = 1;
// 					if($imageFront = $request->file('national_id_front_image')) {
//                         $image_name_front = \PriceHelper::ImageCreateName($imageFront);
//                         $imageFront->move('assets/images/vendorfront', $image_name_front);
//                         $input['national_id_front_image'] = $image_name_front;
//                     }
//                     if($imageBack = $request->file('national_id_back_image')) {
//                         $image_name_back = \PriceHelper::ImageCreateName($imageBack);
//                         $imageBack->move('assets/images/vendorback', $image_name_back);
//                         $input['national_id_back_image'] = $image_name_back;
//                     }
//                     if($licenseImage = $request->file('license_image')) {
//                         $image_name_license = \PriceHelper::ImageCreateName($licenseImage);
//                         $licenseImage->move('assets/images/vendorlicense', $image_name_license);
//                         $input['license_image'] = $image_name_license;
//                     }
//                     if($submerchant_agreement = $request->file('submerchant_agreement')) {
//                         $agreement_name_license = \PriceHelper::ImageCreateName($submerchant_agreement);
//                         $submerchant_agreement->move('assets/images/submerchantagreement', $agreement_name_license);
//                         $input['submerchant_agreement'] = $agreement_name_license;
//                     }
// 			  }
// 			$user->fill($input)->save();
// 	        if($gs->is_verification_email == 1)
// 	        {
// 	        $to = $request->email;
// 	        $subject = 'Verify your email address.';
// 	        $msg = "Dear Customer,<br>We noticed that you need to verify your email address.<br>Simply click the link below to verify. <a href=".url('user/register/verify/'.$token).">".url('user/register/verify/'.$token)."</a>";
// 	        $data = [
// 	            'to' => $to,
// 	            'subject' => $subject,
// 	            'body' => $msg,
// 	        ];
// 	        $mailer = new GeniusMailer();
// 	        $mailer->sendCustomMail($data);
//           	return response()->json('We need to verify your email address. We have sent an email to '.$to.' to verify your email address. Please click link in that email to continue.');
// 	        }
// 	        else {
//             $user->email_verified = 'Yes';
//             $user->update();
// 	        $notification = new Notification;
// 	        $notification->user_id = $user->id;
// 			$notification->save();
// 			$data = [
// 				'to' => $user->email,
// 				'type' => "new_registration",
// 				'cname' => $user->name,
// 				'oamount' => "",
// 				'aname' => "",
// 				'aemail' => "",
// 				'onumber' => "",
// 			];
// 			$mailer = new GeniusMailer();
// 			$mailer->sendAutoMail($data);    
//             Auth::login($user); 
//             echo 1;
// 	        }
//     }

    public function token($token)
    {
        $gs = Generalsetting::findOrFail(1);

        if($gs->is_verification_email == 1)
	    {    	
			$user = User::where('verification_link','=',$token)->first();
			if(isset($user))
			{
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
				return redirect()->route('user-dashboard')->with('success',__('Email Verified Successfully'));
			}
    	}
    	else {
    		return redirect()->back();	
    	}
    }
}