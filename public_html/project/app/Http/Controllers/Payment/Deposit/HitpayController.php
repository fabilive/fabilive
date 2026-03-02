<?php

namespace App\Http\Controllers\Payment\Deposit;

use App\{
    Models\Deposit,
    Models\Transaction,
    Classes\GeniusMailer,
    Models\PaymentGateway,
};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Services\HitPayService;
use Illuminate\Support\Facades\Http;

class HitpayController extends DepositBaseController
{

    protected $hipayService;

    public function __construct(HitPayService $hipayService)
    {
        parent::__construct();
        $this->hipayService = $hipayService;
    }

    // public function store(Request $request)
    // {
    //     $data = PaymentGateway::whereKeyword('hitpay')->first();
    //     $user = $this->user;

    //     $item_amount = $request->amount;
    //     $curr = $this->curr;

    //     $supported_currency = json_decode($data->currency_id, true);
    //     if (!in_array($curr->id, $supported_currency)) {
    //         return redirect()->back()->with('unsuccess', __('Invalid Currency For Hitpay Payment.'));
    //     }

    //     try {
    //         $stripe_secret_key = Config::get('services.hitpay.secret');
    //         \Stripe\Stripe::setApiKey($stripe_secret_key);
    //         $checkout_session = \Stripe\Checkout\Session::create([
    //             "mode" => "payment",
    //             "success_url" => route('deposit.hitpay.notify') . '?session_id={CHECKOUT_SESSION_ID}',
    //             "cancel_url" => route('deposit.payment.cancle'),
    //             "customer_email" => $user->email,
    //             "locale" => "auto",
    //             "line_items" => [
    //                 [
    //                     "quantity" => 1,
    //                     "price_data" => [
    //                         "currency" => $this->curr->name,
    //                         "unit_amount" => $item_amount * 100,
    //                         "product_data" => [
    //                             "name" => $this->gs->title . ' Deposit'
    //                         ]
    //                     ]
    //                 ],
    //             ]
    //         ]);

    //         Session::put('input_data', $request->all());
    //         return redirect($checkout_session->url);
    //     } catch (Exception $e) {
    //         return back()->with('unsuccess', $e->getMessage());
    //     }
    // }

    public function store(Request $request)
    {
        $user = $this->user;
        $amount = $request->amount;
        $currency = 'SGD';
        //$this->curr->name; // like 'USD' or 'INR'

        // Call service method
        $response = $this->hipayService->createPaymentRequest($amount, $currency, 'Wallet Deposit');
        
       
        if (isset($response['url'])) {
            Session::put('input_data', $request->all());
            return redirect($response['url']);
        } else {
            return back()->with('unsuccess', 'Hitpay Error: ' . json_encode($response));
        }
    }

    public function notify(Request $request)
    {
        $input = Session::get('input_data');
        $user = $this->user;

        if ($request->input('status') == 'completed') {
            $amount = $input['amount'] / $this->curr->value;

            $user->balance += $amount;
            $user->mail_sent = 1;
            $user->save();

            $deposit = new Deposit;
            $deposit->user_id = $user->id;
            $deposit->currency = $this->curr->sign;
            $deposit->currency_code = $this->curr->name;
            $deposit->currency_value = $this->curr->value;
            $deposit->amount = $amount;
            $deposit->method = 'Hitpay';
            $deposit->txnid = $request->input('reference');
            $deposit->status = 1;
            $deposit->save();

            // Transaction
            $transaction = new Transaction;
            $transaction->txn_number = Str::random(3) . substr(time(), 6, 8) . Str::random(3);
            $transaction->user_id = $deposit->user_id;
            $transaction->amount = $deposit->amount;
            $transaction->currency_sign  = $deposit->currency;
            $transaction->currency_code  = $deposit->currency_code;
            $transaction->currency_value = $deposit->currency_value;
            $transaction->method = $deposit->method;
            $transaction->txnid = $deposit->txnid;
            $transaction->details = 'Payment Deposit';
            $transaction->type = 'plus';
            $transaction->save();

            // Email
             $data = [
                'to' => $user->email,
                'type' => "wallet_deposit",
                'cname' => $user->name,
                'damount' => $deposit->amount,
                'wbalance' => $user->balance,
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'onumber' => "",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendAutoMail($data);

            return redirect()->route('user-dashboard')->with('success', __('Balance has been added to your account.'));
        } else {
            return redirect()->route('user-dashboard')->with('unsuccess', __('Payment was not successful.'));
        }
    }
}
