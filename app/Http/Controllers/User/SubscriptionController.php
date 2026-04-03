<?php

namespace App\Http\Controllers\User;

use App\Classes\GeniusMailer;
use App\Models\PaymentGateway;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Models\Verification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends UserBaseController
{
    public function package()
    {
        $data['curr'] = $this->curr;
        $data['user'] = $this->user;
        $data['subs'] = Subscription::all();
        $data['package'] = $this->user->subscribes()->where('status', 1)->latest('id')->first();

        return view('user.package.index', $data);
    }

    public function vendorrequest($id)
    {
        $data['curr'] = $this->curr;
        $data['subs'] = Subscription::findOrFail($id);
        $data['user'] = $this->user;
        $data['package'] = $this->user->subscribes()->where('status', 1)->latest('id')->first();
        if ($this->gs->reg_vendor != 1) {
            return redirect()->back();
        }
        // Only redirect fully-approved vendors to their dashboard; others should see the application form
        if ($this->user->is_vendor == 2) {
            return redirect()->route('vendor.dashboard');
        }
        $data['gateway'] = PaymentGateway::whereSubscription(1)->where('currency_id', 'like', "%\"{$this->curr->id}\"%")->latest('id')->get();
        $paystackData = PaymentGateway::whereKeyword('paystack')->first();
        $data['paystack'] = $paystackData ? $paystackData->convertAutoData() : [];
        $data['agreements'] = \App\Models\ManageAgreement::all();

        return view('user.package.details', $data);
    }

    // public function vendorrequestsub(Request $request)
    // {
    //     $input = $request->all();
    //     if(isset($input['method'])){
    //         return redirect()->back();
    //     }
    //     $this->validate($request, [
    //         'shop_name'   => 'unique:users',
    //         'business_registration_certificate' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
    //         'submerchant_agreement' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
    //         'selfie_image' => 'nullable|file|mimes:jpg,jpeg,png,heic,heif,webp|max:20192',
    //         'taxpayer_card_copy' => 'required|file|mimes:jpg,jpeg,png,pdf',
    //         'id_card_copy' => 'required|file|mimes:jpg,jpeg,png,pdf',
    //         'passport_copy' => 'required|file|mimes:jpg,jpeg,png,pdf',
    //         'driver_license_copy' => 'required|file|mimes:jpg,jpeg,png,pdf',
    //         'residence_permit' => 'required|file|mimes:jpg,jpeg,png,pdf',
    //       ],[
    //           'shop_name.unique' => __('This shop name has already been taken.')
    //         ]);
    //         if(\DB::table('pages')->where('slug',$request->shop_name)->exists())
    //         {
    //             return redirect()->back()->with('unsuccess',__('This shop name has already been taken.'));
    //         }
    //         $success_url = route('user.payment.return');
    //         $user = $this->user;
    //         $uploadPath = public_path('assets/uploads/documents');
    //         if ($request->hasFile('business_registration_certificate')) {
    //             $file = $request->file('business_registration_certificate');
    //             $filename = time().'_business.'.$file->getClientOriginalExtension();
    //             $file->move($uploadPath, $filename);
    //             $input['business_registration_certificate'] = 'assets/uploads/documents/'.$filename;
    //         }
    //         if ($request->hasFile('taxpayer_card_copy')) {
    //             $file = $request->file('taxpayer_card_copy');
    //             $filename = time().'_taxpayer.'.$file->getClientOriginalExtension();
    //             $file->move($uploadPath, $filename);
    //             $input['taxpayer_card_copy'] = 'assets/uploads/documents/'.$filename;
    //         }
    //         if ($request->hasFile('id_card_copy')) {
    //             $file = $request->file('id_card_copy');
    //             $filename = time().'_id.'.$file->getClientOriginalExtension();
    //             $file->move($uploadPath, $filename);
    //             $input['id_card_copy'] = 'assets/uploads/documents/'.$filename;
    //         }
    //         if ($request->hasFile('passport_copy')) {
    //             $file = $request->file('passport_copy');
    //             $filename = time().'_passport.'.$file->getClientOriginalExtension();
    //             $file->move($uploadPath, $filename);
    //             $input['passport_copy'] = 'assets/uploads/documents/'.$filename;
    //         }
    //         if ($request->hasFile('driver_license_copy')) {
    //             $file = $request->file('driver_license_copy');
    //             $filename = time().'_license.'.$file->getClientOriginalExtension();
    //             $file->move($uploadPath, $filename);
    //             $input['driver_license_copy'] = 'assets/uploads/documents/'.$filename;
    //         }
    //         if ($request->hasFile('residence_permit')) {
    //             $file = $request->file('residence_permit');
    //             $filename = time().'_residence.'.$file->getClientOriginalExtension();
    //             $file->move($uploadPath, $filename);
    //             $input['residence_permit'] = 'assets/uploads/documents/'.$filename;
    //         }
    //         if ($request->hasFile('selfie_image')) {
    //             $file = $request->file('selfie_image');
    //             $filename = time().'_selfie.'.$file->getClientOriginalExtension();
    //             $file->move($uploadPath, $filename);
    //             $input['selfie_image'] = 'assets/uploads/documents/'.$filename;
    //         }
    //         if ($request->hasFile('submerchant_agreement')) {
    //             $file = $request->file('submerchant_agreement');
    //             $filename = time().'_submerchant.'.$file->getClientOriginalExtension();
    //             $file->move($uploadPath, $filename);
    //             $input['submerchant_agreement'] = 'assets/uploads/documents/'.$filename;
    //         }
    //         $subs = Subscription::findOrFail($request->subs_id);
    //         $user->is_vendor = 2;
    //         $user->date = date('Y-m-d', strtotime(Carbon::now()->format('Y-m-d').' + '.$subs->days.' days'));
    //         $user->mail_sent = 1;
    //         $user->update($input);
    //         $sub = new UserSubscription;
    //         $data = json_decode(json_encode($subs), true);
    //         $data['user_id'] = $user->id;
    //         $data['subscription_id'] = $subs->id;
    //         $data['method'] = 'Free';
    //         $data['status'] = 1;
    //         $sub->currency_sign = $this->curr->sign;
    //         $sub->currency_code = $this->curr->name;
    //         $sub->currency_value = $this->curr->value;
    //         $sub->fill($data)->save();
    //         $data = [
    //             'to' => $user->email,
    //             'type' => "vendor_accept",
    //             'cname' => $user->name,
    //             'oamount' => "",
    //             'aname' => "",
    //             'aemail' => "",
    //             'onumber' => "",
    //         ];
    //         $mailer = new GeniusMailer();
    //         $mailer->sendAutoMail($data);
    //         return redirect($success_url)->with('success',Auth::user()->is_vendor == 2 ? __('Vendor Account Activated Successfully') : __('Vendor Application Submitted Successfully. Please wait for admin approval.'));
    // }

    public function vendorrequestsub(Request $request)
    {
        $input = $request->all();
        if (isset($input['method'])) {
            return redirect()->back();
        }
        $this->validate($request, [
            'shop_name' => 'unique:users',
            'business_registration_certificate' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/heic,image/heif,application/pdf',

            'passport_copy' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/heic,image/heif,application/pdf',
            'id_card_copy' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/heic,image/heif,application/pdf',
            'driver_license_copy' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/heic,image/heif,application/pdf',

            'selfie_image' => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,image/gif',
            'taxpayer_card_copy' => 'required|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/heic,image/heif,application/pdf',
            'residence_permit' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/heic,image/heif,application/pdf',
            'submerchant_agreement' => 'required|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/bmp,image/heic,image/heif,application/pdf',
        ], [
            'shop_name.unique' => __('This shop name has already been taken.'),
            'taxpayer_card_copy.required' => __('The Taxpayer Card Copy is required.'),
            'taxpayer_card_copy.mimetypes' => __('The taxpayer card must be an image (jpg, png, webp, etc.) or PDF.'),
            'passport_copy.mimetypes' => __('The passport copy must be an image (jpg, png, webp, etc.) or PDF.'),
            'id_card_copy.mimetypes' => __('The ID card copy must be an image (jpg, png, webp, etc.) or PDF.'),
            'driver_license_copy.mimetypes' => __('The driver license copy must be an image (jpg, png, webp, etc.) or PDF.'),
            'business_registration_certificate.mimetypes' => __('The business certificate must be an image (jpg, png, webp, etc.) or PDF.'),
            'submerchant_agreement.required' => __('The Sub-Merchant Agreement is required.'),
            'submerchant_agreement.mimetypes' => __('The sub-merchant agreement must be an image (jpg, png, webp, etc.) or PDF.'),
        ]);
        if (
            ! $request->hasFile('passport_copy') &&
            ! $request->hasFile('id_card_copy') &&
            ! $request->hasFile('driver_license_copy')
        ) {
            return redirect()->back()
                ->withErrors([
                    'identity_document' => __('Please upload at least ONE document: Passport, National ID Card, or Driver License.'),
                ])
                ->withInput();
        }

        // if (!$request->hasFile('selfie_image')) {
        //     return redirect()->back()
        //         ->withErrors([
        //             'selfie_image' => __('Please capture and upload a selfie image.')
        //         ])
        //         ->withInput();
        // }

        if (\DB::table('pages')->where('slug', $request->shop_name)->exists()) {
            return redirect()->back()->with('unsuccess', __('This shop name has already been taken.'));
        }
        $success_url = route('user.payment.return');
        $user = $this->user;
        $uploadPath = public_path('assets/images/attachments');
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $attachments = [];
        if ($request->hasFile('selfie_image')) {
            $file = $request->file('selfie_image');
            $filename = time().'_selfie.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $input['selfie_image'] = 'assets/images/attachments/'.$filename;
            $attachments[] = $filename;
        }
        if ($request->hasFile('business_registration_certificate')) {
            $file = $request->file('business_registration_certificate');
            $filename = time().'_business.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $input['business_registration_certificate'] = 'assets/images/attachments/'.$filename;
            $attachments[] = $filename;
        }
        if ($request->hasFile('taxpayer_card_copy')) {
            $file = $request->file('taxpayer_card_copy');
            $filename = time().'_taxpayer.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $input['taxpayer_card_copy'] = 'assets/images/attachments/'.$filename;
            $attachments[] = $filename;
        }
        if ($request->hasFile('id_card_copy')) {
            $file = $request->file('id_card_copy');
            $filename = time().'_id.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $input['id_card_copy'] = 'assets/images/attachments/'.$filename;
            $attachments[] = $filename;
        }
        if ($request->hasFile('passport_copy')) {
            $file = $request->file('passport_copy');
            $filename = time().'_passport.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $input['passport_copy'] = 'assets/images/attachments/'.$filename;
            $attachments[] = $filename;
        }
        if ($request->hasFile('driver_license_copy')) {
            $file = $request->file('driver_license_copy');
            $filename = time().'_license.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $input['driver_license_copy'] = 'assets/images/attachments/'.$filename;
            $attachments[] = $filename;
        }
        if ($request->hasFile('residence_permit')) {
            $file = $request->file('residence_permit');
            $filename = time().'_residence.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $input['residence_permit'] = 'assets/images/attachments/'.$filename;
            $attachments[] = $filename;
        }
        if ($request->hasFile('submerchant_agreement')) {
            $file = $request->file('submerchant_agreement');
            $filename = time().'_submerchant.'.$file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $input['submerchant_agreement'] = 'assets/images/attachments/'.$filename;
            $attachments[] = $filename;
        }
        $subs = Subscription::findOrFail($request->subs_id);
        $user->is_vendor = ($user->is_vendor == 2) ? 2 : 1;
        $user->date = date('Y-m-d', strtotime(Carbon::now()->format('Y-m-d').' + '.$subs->days.' days'));
        $user->mail_sent = 1;
        $user->update($input);
        $sub = new UserSubscription;
        $data = json_decode(json_encode($subs), true);
        $data['user_id'] = $user->id;
        $data['subscription_id'] = $subs->id;
        $data['method'] = 'Free';
        $data['status'] = 0; // Pending — admin must approve to set is_vendor=2
        $sub->currency_sign = $this->curr->sign;
        $sub->currency_code = $this->curr->name;
        $sub->currency_value = $this->curr->value;
        $sub->fill($data)->save();

        if (count($attachments) > 0) {
            $ver = new Verification();
            $ver->user_id = $user->id;
            $ver->attachments = implode(',', $attachments);
            $ver->text = $request->message;
            $ver->status = 'Pending';
            $ver->save();
        }

        $data = [
            'to' => $user->email,
            'type' => 'vendor_accept',
            'cname' => $user->name,
            'oamount' => '',
            'aname' => '',
            'aemail' => '',
            'onumber' => '',
        ];
        $mailer = new GeniusMailer();
        $mailer->sendAutoMail($data);

        return redirect($success_url)->with('success', __('Vendor Application Submitted Successfully. Please wait for admin approval.'));
    }

    public function paycancle()
    {
        return redirect()->back()->with('unsuccess', __('Payment Cancelled.'));
    }

    public function payreturn()
    {
        return redirect()->route('user-dashboard')->with('success', Auth::user()->is_vendor == 2 ? __('Vendor Account Activated Successfully') : __('Vendor Application Submitted Successfully. Please wait for admin approval.'));
    }

    public function check(Request $request)
    {

        //--- Validation Section
        $input = $request->all();
        $rules = ['shop_name' => 'unique:users'];
        $customs = ['shop_name.unique' => __('This shop name has already been taken.')];
        $validator = \Validator::make($input, $rules, $customs);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        return response()->json('success');
    }
}
