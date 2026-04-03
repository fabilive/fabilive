<?php

namespace App\Http\Controllers\User;

use App\Models\Currency;
use App\Models\Deposit;
use App\Models\PaymentGateway;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DepositController extends UserBaseController
{
    public function index()
    {
        return view('user.deposit.index');
    }

    public function transactions()
    {
        return view('user.transactions');
    }

    public function transhow($id)
    {
        $data = Transaction::find($id);

        return view('load.transaction-details', compact('data'));
    }

    // public function create() {
    //   $data['curr'] = $this->curr;
    //   $data['gateway']  = PaymentGateway::whereDeposit(1)->where('currency_id', 'like',
    //   "%\"{$this->curr->id}\"%")->latest('id')->get();
    //   $paystackData = PaymentGateway::whereKeyword('paystack')->first();
    //   $data['paystack'] = $paystackData->convertAutoData();
    //   return view('user.deposit.create', $data);
    // }

    public function create()
    {
        $data['curr'] = $this->curr;
        $data['gateway'] = PaymentGateway::whereDeposit(1)
            ->where('currency_id', 'like', "%\"{$this->curr->id}\"%")
            ->latest('id')->get();

        $paystackData = PaymentGateway::whereKeyword('paystack')->first();
        $data['paystack'] = $paystackData->convertAutoData();

        // Add user and currency for consistent display
        $data['user'] = Auth::user();
        $data['sign'] = \App\Models\Currency::where('is_default', 1)->first();

        return view('user.deposit.create', $data);
    }

    public function paycancle()
    {
        return redirect()->back()->with('unsuccess', __('Payment Cancelled.'));
    }

    public function payreturn()
    {
        return redirect()->route('user-dashboard')->with('success', __('Balance has been added to your account.'));
    }

    public function sendDeposit($number)
    {
        $deposit = Deposit::where('deposit_number', $number)->first();

        $curr = Currency::where('name', '=', $deposit->currency_code)->firstOrFail();
        $gateways = PaymentGateway::scopeHasGateway($curr->id);
        $paystack = PaymentGateway::whereKeyword('paystack')->first();
        $paystackData = $paystack->convertAutoData();

        if ($deposit->status == 1) {
            return response()->json(['status' => false, 'data' => [], 'error' => 'Deposit Allready Added.']);
        }

        return view('user.deposit.payment', compact('deposit', 'gateways', 'paystackData'));
    }
}
