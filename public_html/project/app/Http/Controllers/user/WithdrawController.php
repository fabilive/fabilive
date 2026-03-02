<?php

namespace App\Http\Controllers\User;

use App\{
    Models\User,
    Models\Withdraw,
    Models\Currency
};

use Illuminate\Http\Request;

class WithdrawController extends UserBaseController
{

  	public function index()
    {
        $withdraws = Withdraw::where('user_id','=',$this->user->id)->where('type','=','user')->latest('id')->get();
        $sign = Currency::where('is_default','=',1)->first();
        return view('user.withdraw.index',compact('withdraws','sign'));
    }
    public function create()
    {
        $sign = Currency::where('is_default','=',1)->first();
        return view('user.withdraw.withdraw' ,compact('sign'));
    }

    public function store(Request $request)
{
    try {
        dd($request->all()); // Remove this in production

        $from = $this->user;
        // dd($from);
        $withdrawcharge = $this->gs;
        $charge = $withdrawcharge->withdraw_fee;

        if ($request->amount <= 0) {
            return response()->json(['errors' => [0 => __('Please enter a valid amount.')]]);
        }

        $amount = $request->amount;

        if ($from->balance < $amount) {
            return response()->json(['errors' => [0 => __('Insufficient Balance.')]]);
        }

        $fee = (($withdrawcharge->withdraw_charge / 100) * $amount) + $charge;
        $finalamount = $amount - $fee;

        if ($finalamount < 0) {
            return response()->json(['errors' => [0 => __('Withdraw amount is too low.')]]);
        }

        $finalamount = number_format((float)$finalamount, 2, '.', '');
        $from->balance -= $amount;
        $from->update();

        $newwithdraw = new Withdraw();

        if ($request->methods == 'Campay') {
            if ($request->network) $newwithdraw['network'] = $request->network;
            if ($request->campay_acc_no) $newwithdraw['campay_acc_no'] = $request->campay_acc_no;
            if ($request->campay_acc_name) $newwithdraw['campay_acc_name'] = $request->campay_acc_name;
        }

        $newwithdraw['user_id'] = $this->user->id;
        $newwithdraw['method'] = $request->methods;
        $newwithdraw['acc_email'] = $request->acc_email;
        $newwithdraw['iban'] = $request->iban;
        $newwithdraw['country'] = $request->acc_country;
        $newwithdraw['acc_name'] = $request->acc_name;
        $newwithdraw['address'] = $request->address;
        $newwithdraw['swift'] = $request->swift;
        $newwithdraw['reference'] = $request->reference;
        $newwithdraw['amount'] = $finalamount;
        $newwithdraw['fee'] = $fee;
        $newwithdraw['type'] = 'user';

        $newwithdraw->save();

        return response()->json(__('Withdrawal has been submitted successfully and it will be completed on time'));

    } catch (\Exception $e) {
        // Return exact error message for debugging
        return response()->json(['errors' => [0 => $e->getMessage()]], 500);
    } catch (\Throwable $t) {
        // Catch any PHP 7+ fatal errors
        return response()->json(['errors' => [0 => $t->getMessage()]], 500);
    }
}

}