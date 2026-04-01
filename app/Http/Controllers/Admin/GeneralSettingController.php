<?php

namespace App\Http\Controllers\Admin;

use App\Models\Generalsetting;
use Illuminate\{
    Http\Request,
    Support\Facades\Mail
};

use Config;
use Validator;

class GeneralSettingController extends AdminBaseController
{
    protected $rules =
    [
        'logo'              => 'mimes:jpeg,jpg,png,svg',
        'favicon'           => 'mimes:jpeg,jpg,png,svg',
        'loader'            => 'mimes:gif',
        'admin_loader'      => 'mimes:gif',
        'affilate_banner'   => 'mimes:jpeg,jpg,png,svg',
        'error_banner_404'  => 'mimes:jpeg,jpg,png,svg',
        'error_banner_500'  => 'mimes:jpeg,jpg,png,svg',
        'popup_background'  => 'mimes:jpeg,jpg,png,svg',
        'invoice_logo'      => 'mimes:jpeg,jpg,png,svg',
        'user_image'        => 'mimes:jpeg,jpg,png,svg',
        'footer_logo'       => 'mimes:jpeg,jpg,png,svg',
    ];

    private function setEnv($key, $value,$prev)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . $prev,
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }

    public function paymentsinfo(){
        return view('admin.generalsetting.paymentsinfo');
    }

    public function logo(){
        return view('admin.generalsetting.logo');
    }

    public function favicon(){
        return view('admin.generalsetting.favicon');
    }

    public function loader(){
        return view('admin.generalsetting.loader');
    }

    public function websitecontent(){
        return view('admin.generalsetting.websitecontent');
    }
    public function popup(){
        return view('admin.generalsetting.popup');
    }
    public function breadcrumb(){
        return view('admin.generalsetting.breadcrumb');
    }

    public function footer(){
        return view('admin.generalsetting.footer');
    }

    public function affilate(){
        return view('admin.generalsetting.affilate');
    }

    public function error_banner(){
        return view('admin.generalsetting.error_banner');
    }



    public function maintain(){
        return view('admin.generalsetting.maintain');
    }



    public function vendor_color(){
        return view('admin.generalsetting.vendor_color');
    }

    public function user_image(){
        return view('admin.generalsetting.user_image');
    }

    // Genereal Settings All post requests will be done in this method
    public function generalupdate(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        try {
            $data = Generalsetting::findOrFail(1);
            $updateData = []; // Only update what is actually POSTed

            // File uploads
            if ($file = $request->file('logo')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->logo);
                $updateData['logo'] = $name;
            }
            if ($file = $request->file('favicon')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->favicon);
                $updateData['favicon'] = $name;
            }
            if ($file = $request->file('deal_background')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->deal_background);
                $updateData['deal_background'] = $name;
            }
            if ($file = $request->file('breadcrumb_banner')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->breadcrumb_banner);
                $updateData['breadcrumb_banner'] = $name;
            }
            if ($file = $request->file('loader')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->loader);
                $updateData['loader'] = $name;
            }
            if ($file = $request->file('admin_loader')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->admin_loader);
                $updateData['admin_loader'] = $name;
            }
            if ($file = $request->file('affilate_banner')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->affilate_banner);
                $updateData['affilate_banner'] = $name;
            }
            if ($file = $request->file('error_banner_404')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->error_banner_404);
                $updateData['error_banner_404'] = $name;
            }
            if ($file = $request->file('error_banner_500')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->error_banner_500);
                $updateData['error_banner_500'] = $name;
            }
            if ($file = $request->file('popup_background')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->popup_background);
                $updateData['popup_background'] = $name;
            }
            if ($file = $request->file('invoice_logo')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->invoice_logo);
                $updateData['invoice_logo'] = $name;
            }
            if ($file = $request->file('user_image')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->user_image);
                $updateData['user_image'] = $name;
            }
            if ($file = $request->file('footer_logo')) {
                $name = \PriceHelper::ImageCreateName($file);
                $data->upload($name, $file, $data->footer_logo);
                $updateData['footer_logo'] = $name;
            }

            // Non-file scalar fields — only update if present in request
            $scalarFields = [
                'title', 'copyright', 'colors', 'talkto', 'disqus', 'currency_format',
                'withdraw_fee', 'withdraw_charge', 'shipping_cost', 'mail_driver', 'mail_host',
                'mail_port', 'mail_encryption', 'mail_user', 'mail_pass', 'from_email', 'from_name',
                'is_affilate', 'affilate_charge', 'fixed_commission', 'percentage_commission',
                'multiple_shipping', 'vendor_ship_info', 'is_verification_email', 'wholesell',
                'is_capcha', 'popup_title', 'popup_text', 'is_secure', 'paypal_business',
                'paytm_merchant', 'maintain_text', 'header_color', 'capcha_secret_key',
                'capcha_site_key', 'partner_title', 'partner_text', 'deal_title', 'deal_details',
                'deal_time', 'delivery_base_fee', 'delivery_stopover_fee', 'rider_percentage_commission',
                'referral_amount', 'referral_bonus', 'custom_referral_bonus', 'same_servicearea_delivery_fee',
                'vendor_color',
            ];
            foreach ($scalarFields as $field) {
                if ($request->exists($field)) {
                    $updateData[$field] = $request->input($field);
                }
            }

            // Product page checkboxes
            if ($request->exists('product_page')) {
                $updateData['product_page'] = !empty($request->product_page)
                    ? implode(',', $request->product_page)
                    : null;
            }

            // ENV updates for captcha keys
            if ($request->capcha_secret_key) {
                $this->setEnv('NOCAPTCHA_SECRET', $request->capcha_secret_key, env('NOCAPTCHA_SECRET'));
            }
            if ($request->capcha_site_key) {
                $this->setEnv('NOCAPTCHA_SITEKEY', $request->capcha_site_key, env('NOCAPTCHA_SITEKEY'));
            }

            cache()->forget('generalsettings');
            $data->update($updateData);

            $msg = __('Data Updated Successfully.');
            return response()->json($msg);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('GeneralSetting update failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => ['Upload failed: ' . $e->getMessage()]]], 500);
        }
    }

    public function generalupdatepayment(Request $request)
    {
        //--- Validation Section
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        else {
        $input = $request->all();
        $data = Generalsetting::findOrFail(1);
        $prev = $data->molly_key;

        if ($request->vendor_ship_info == ""){
            $input['vendor_ship_info'] = 0;
        }

        if ($request->instamojo_sandbox == ""){
            $input['instamojo_sandbox'] = 0;
        }

        if ($request->paypal_mode == ""){
            $input['paypal_mode'] = 'live';
        }
        else {
            $input['paypal_mode'] = 'sandbox';
        }

        if ($request->paytm_mode == ""){
            $input['paytm_mode'] = 'live';
        }
        else {
            $input['paytm_mode'] = 'sandbox';
        }
        $data->update($input);

        cache()->forget('generalsettings');

        // Set Molly ENV

        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __(__('Data Updated Successfully.'));
        return response()->json($msg);
        //--- Redirect Section Ends
        }
    }

    public function generalMailUpdate(Request $request)
    {
        $input = $request->all();
        $maildata = Generalsetting::findOrFail(1);

        // Config::set('mail.driver', $request->mail_driver);
        // Config::set('mail.host', $request->mail_host);
        // Config::set('mail.port', $request->mail_port);
        // Config::set('mail.encryption', $request->mail_encryption);
        // Config::set('mail.username', $request->mail_user);
        // Config::set('mail.password', $request->mail_pass);

        //     $datas = [
        //             'to' => 'junajunnun@gmail.com',
        //             'subject' => 'Test Sms',
        //             'body' => 'Test Body',
        //     ];

        //     $data = [
        //         'email_body' => $datas['body']
        //     ];

        //     $objDemo = new \stdClass();
        //     $objDemo->to = $datas['to'];
        //     $objDemo->from = $request->from_email;
        //     $objDemo->title = $request->from_name;
        //     $objDemo->subject = $datas['subject'];
        //     try{
        //         Mail::send('admin.email.mailbody',$data, function ($message) use ($objDemo) {
        //             $message->from($objDemo->from,$objDemo->title);
        //             $message->to($objDemo->to);
        //             $message->subject($objDemo->subject);
        //         });
        //     }
        //     catch (\Exception $e){
        //         return response()->json($e->getMessage());
        //     }


        $maildata->update($input);

        //--- Redirect Section
        $msg = 'Mail Data Updated Successfully.';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function isreward($status)
    {
        $data = Generalsetting::findOrFail(1);
        $data->is_reward = $status;
        $data->update();
        cache()->forget('generalsettings');
    }

    // Status Change Method -> GET Request
    public function status($field,$value)
    {
        $prev = '';
        $data = Generalsetting::findOrFail(1);
        if($field == 'is_debug'){
            $prev = $data->is_debug == 1 ? 'true':'false';
        }
        $data[$field] = $value;
        $data->update();
        if($field == 'is_debug'){
            $now = $data->is_debug == 1 ? 'true':'false';
            $this->setEnv('APP_DEBUG',$now,$prev);
        }
        cache()->forget('generalsettings');
        //--- Redirect Section
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

    }
}
