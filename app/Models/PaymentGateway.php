<?php

namespace App\Models;

use Illuminate\{
    Database\Eloquent\Model
};

class PaymentGateway extends Model
{
    protected $fillable = ['title', 'details', 'subtitle', 'name', 'type', 'information', 'currency_id'];

    public $timestamps = false;

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency')->withDefault();
    }

    public static function scopeHasGateway($curr)
    {
        return PaymentGateway::where('currency_id', 'like', "%\"{$curr}\"%")->orwhere('currency_id', '*')->get();
    }

    public function convertAutoData()
    {
        return json_decode($this->information, true);
    }

    public function getAutoDataText()
    {
        $text = $this->convertAutoData();

        return end($text);
    }

    public function showKeyword()
    {
        $data = $this->keyword == null ? 'other' : $this->keyword;

        return $data;
    }

    public function showCheckoutLink()
    {
        $link = '';
        $data = $this->keyword == null ? 'other' : $this->keyword;
        if ($data == 'paypal') {
            $link = route('front.paypal.submit');
        } elseif ($data == 'stripe') {
            $link = route('front.stripe.submit');
        } elseif ($data == 'instamojo') {
            $link = route('front.instamojo.submit');
        } elseif ($data == 'paystack') {
            $link = route('front.paystack.submit');
        } elseif ($data == 'paytm') {
            $link = route('front.paytm.submit');
        } elseif ($data == 'mollie') {
            $link = route('front.molly.submit');
        } elseif ($data == 'razorpay') {
            $link = route('front.razorpay.submit');
        } elseif ($data == 'campay') {
            $link = route('front.payment.campay');
        } elseif ($data == 'hitpay') {
            $link = route('front.payment.hitpay');
        } elseif ($data == 'authorize.net') {
            $link = route('front.authorize.submit');
        } elseif ($data == 'mercadopago') {
            $link = route('front.mercadopago.submit');
        } elseif ($data == 'flutterwave') {
            $link = route('front.flutter.submit');
        } elseif ($data == '2checkout') {
            $link = route('front.twocheckout.submit');
        } elseif ($data == 'sslcommerz') {
            $link = route('front.ssl.submit');
        } elseif ($data == 'voguepay') {
            $link = route('front.voguepay.submit');
        } elseif ($data == 'cod') {
            $link = route('front.cod.submit');
        } else {
            $link = route('front.manual.submit');
        }

        return $link;
    }

    public function showDepositLink()
    {
        $link = '';
        $data = $this->keyword;
        if ($data == 'paypal') {
            $link = route('deposit.paypal.submit');
        } elseif ($data == 'stripe') {
            $link = route('deposit.stripe.submit');
        } elseif ($data == 'instamojo') {
            $link = route('deposit.instamojo.submit');
        } elseif ($data == 'paystack') {
            $link = route('deposit.paystack.submit');
        } elseif ($data == 'paytm') {
            $link = route('deposit.paytm.submit');
        } elseif ($data == 'mollie') {
            $link = route('deposit.molly.submit');
        } elseif ($data == 'razorpay') {
            $link = route('deposit.razorpay.submit');
        } elseif ($data == 'campay') {
            $link = route('deposit.campay.submit');
        } elseif ($data == 'authorize.net') {
            $link = route('deposit.authorize.submit');
        } elseif ($data == 'mercadopago') {
            $link = route('deposit.mercadopago.submit');
        } elseif ($data == 'flutterwave') {
            $link = route('deposit.flutter.submit');
        } elseif ($data == '2checkout') {
            $link = route('deposit.twocheckout.submit');
        } elseif ($data == 'sslcommerz') {
            $link = route('deposit.ssl.submit');
        } elseif ($data == 'voguepay') {
            $link = route('deposit.voguepay.submit');
        } elseif ($data == null) {
            $link = route('deposit.manual.submit');
        } elseif ($data == 'hitpay') {
            $link = route('deposit.hitpay.submit');
        }

        return $link;
    }

    public function ApishowDepositLink()
    {
        $link = '';
        $data = $this->keyword;
        if ($data == 'paypal') {
            $link = route('api.user.deposit.paypal.submit');
        } elseif ($data == 'stripe') {
            $link = route('api.user.deposit.stripe.submit');
        } elseif ($data == 'instamojo') {
            $link = route('api.user.deposit.instamojo.submit');
        } elseif ($data == 'paystack') {
            $link = route('api.user.deposit.paystack.submit');
        } elseif ($data == 'paytm') {
            $link = route('api.user.deposit.paytm.submit');
        } elseif ($data == 'mollie') {
            $link = route('api.user.deposit.molly.submit');
        } elseif ($data == 'razorpay') {
            $link = route('api.user.deposit.razorpay.submit');
        } elseif ($data == 'authorize.net') {
            $link = route('api.user.deposit.authorize.submit');
        } elseif ($data == 'mercadopago') {
            $link = route('api.user.deposit.mercadopago.submit');
        } elseif ($data == 'flutterwave') {
            $link = route('api.user.deposit.flutter.submit');
        } elseif ($data == '2checkout') {
            $link = route('api.user.deposit.twocheckout.submit');
        } elseif ($data == 'sslcommerz') {
            $link = route('api.user.deposit.ssl.submit');
        } elseif ($data == 'voguepay') {
            $link = route('api.user.deposit.voguepay.submit');
        } elseif ($data == null) {
            $link = route('api.user.deposit.manual.submit');
        }

        return $link;
    }

    public function showForm()
    {
        $show = '';
        $data = $this->keyword == null ? 'other' : $this->keyword;
        $values = ['cod', 'voguepay', 'sslcommerz', 'flutterwave', 'razorpay', 'mollie', 'paytm', 'paystack', 'paypal',
            'instamojo', 'stripe', 'campay', 'hitpay'];
        if (in_array($data, $values)) {
            $show = 'no';
        } else {
            $show = 'yes';
        }

        return $show;
    }

    public function showApiCheckoutLink()
    {
        $link = '';
        $data = $this->keyword == null ? 'other' : $this->keyword;
        if ($data == 'paypal') {
            $link = route('api.paypal.submit');
        } elseif ($data == 'stripe') {
            $link = route('api.stripe.submit');
        } elseif ($data == 'instamojo') {
            $link = route('api.instamojo.submit');
        } elseif ($data == 'paystack') {
            $link = route('api.paystack.submit');
        } elseif ($data == 'paytm') {
            $link = route('api.paytm.submit');
        } elseif ($data == 'mollie') {
            $link = route('api.molly.submit');
        } elseif ($data == 'razorpay') {
            $link = route('api.razorpay.submit');
        } elseif ($data == 'authorize.net') {
            $link = route('api.authorize.submit');
        } elseif ($data == 'mercadopago') {
            $link = route('api.mercadopago.submit');
        } elseif ($data == 'flutterwave') {
            $link = route('api.flutter.submit');
        } elseif ($data == '2checkout') {
            $link = route('api.twocheckout.submit');
        } elseif ($data == 'sslcommerz') {
            $link = route('api.ssl.submit');
        } elseif ($data == 'voguepay') {
            $link = route('api.voguepay.submit');
        } elseif ($data == 'cod') {
            $link = route('api.cod.submit');
        } else {
            $link = route('api.manual.submit');
        }

        return $link;
    }

    public function showSubscriptionLink()
    {
        $link = '';
        $data = $this->keyword;
        if ($data == 'paypal') {
            $link = route('user.paypal.submit');
        } elseif ($data == 'stripe') {
            $link = route('user.stripe.submit');
        } elseif ($data == 'instamojo') {
            $link = route('user.instamojo.submit');
        } elseif ($data == 'paystack') {
            $link = route('user.paystack.submit');
        } elseif ($data == 'paytm') {
            $link = route('user.paytm.submit');
        } elseif ($data == 'mollie') {
            $link = route('user.molly.submit');
        } elseif ($data == 'razorpay') {
            $link = route('user.razorpay.submit');
        } elseif ($data == 'authorize.net') {
            $link = route('user.authorize.submit');
        } elseif ($data == 'mercadopago') {
            $link = route('user.mercadopago.submit');
        } elseif ($data == 'flutterwave') {
            $link = route('user.flutter.submit');
        } elseif ($data == '2checkout') {
            $link = route('user.twocheckout.submit');
        } elseif ($data == 'sslcommerz') {
            $link = route('user.ssl.submit');
        } elseif ($data == 'voguepay') {
            $link = route('user.voguepay.submit');
        } elseif ($data == null) {
            $link = route('user.manual.submit');
        }

        return $link;
    }
}
