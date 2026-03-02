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
use App\Services\CampayService;


class CampayController extends DepositBaseController
{

    protected $campayService;

    public function __construct(CampayService $campayService)
    {
        parent::__construct();
        $this->campayService = $campayService;
    }



    public function store(Request $request)
    {

        $data = PaymentGateway::whereKeyword('campay')->first();
        $user = $this->user;

        $item_amount = $request->amount;
        $curr = $this->curr;

        $supported_currency = json_decode($data->currency_id, true);
        if (!in_array($curr->id, $supported_currency)) {
            return redirect()->back()->with('unsuccess', __('Invalid Currency For Campay Payment.'));
        }


        try {
            $response = $this->campayService->generatePaymentLink(
                $this->curr->name,
                $item_amount * 100,
                'This is the test payment',
                route('deposit.campay.notify')
            );

            if (isset($response['link'])) {
                Session::put('input_data', $request->all());
                if (!$response || !isset($response['link'])) {
                    return back()->with('error', 'Failed to generate payment link');
                }
                //return redirect()->route('deposit.campay.notify');
                return redirect()->away($response['link']);
            } else {
                return back()->with('unsuccess', 'Failed to generate payment link');
            }
        } catch (Exception $e) {
            return back()->with('unsuccess', $e->getMessage());
        }
    }


    public function notify(Request $request)
    {
        $input = Session::get('input_data');

        $user = $this->user;

        if (strtolower($request->status) === 'successful') {

            $user->balance = $user->balance + ($input['amount'] / $this->curr->value);
            $user->mail_sent = 1;
            $user->save();

            $deposit = new Deposit;
            $deposit->user_id = $user->id;
            $deposit->currency = $this->curr->sign;
            $deposit->currency_code = $this->curr->name;
            $deposit->currency_value = $this->curr->value;
            $deposit->amount = $input['amount'] / $this->curr->value;
            $deposit->method = 'Campay';
            $deposit->txnid = $request->reference;
            $deposit->status = 1;
            $deposit->save();


            // store in transaction table
            if ($deposit->status == 1) {
                $transaction = new Transaction;
                $transaction->txn_number = Str::random(3) . substr(time(), 6, 8) . Str::random(3);
                $transaction->user_id = $deposit->user_id;
                $transaction->amount = $deposit->amount;
                $transaction->user_id = $deposit->user_id;
                $transaction->currency_sign  = $deposit->currency;
                $transaction->currency_code  = $deposit->currency_code;
                $transaction->currency_value = $deposit->currency_value;
                $transaction->method = $deposit->method;
                $transaction->txnid = $deposit->txnid;
                $transaction->details = 'Payment Deposit';
                $transaction->type = 'plus';
                $transaction->save();
            }

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
        }else{
            return redirect()->route('user-dashboard')->with('unsuccess', __('Failed to Deposit.'));
        }
    }
}
