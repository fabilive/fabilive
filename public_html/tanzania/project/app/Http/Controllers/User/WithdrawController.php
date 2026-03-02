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
        $from = $this->user;
        $withdrawcharge = $this->gs;
        $charge = $withdrawcharge->withdraw_fee;
        
        if ($request->amount > 0) {
            $amount = $request->amount;
            if ($from->affilate_income >= $amount) {
                $fee = (($withdrawcharge->withdraw_charge / 100) * $amount) + $charge;
                $finalamount = $amount - $fee;
                if ($finalamount < 0) {
                    return response()->json(array('errors' => [0 => __('Withdraw amount is too low.')]));
                }
                $finalamount = number_format((float)$finalamount, 2, '.', '');
                $from->affilate_income = $from->affilate_income - $amount;
                $from->update();
                $newwithdraw = new Withdraw();
                if ($request->methods == 'Pesapal') {
                    if ($request->network) {
                        $newwithdraw['network'] = $request->network;
                    }
                    if ($request->pesapal_acc_no) {
                        $newwithdraw['pesapal_acc_no'] = $request->pesapal_acc_no;
                    }
                    if ($request->pesapal_acc_name) {
                        $newwithdraw['pesapal_acc_name'] = $request->pesapal_acc_name;
                    }
                }
                $newwithdraw['user_id'] = $this->user->id;
                $newwithdraw['method'] = $request->methods;
                $newwithdraw['acc_email'] = $request->acc_email;
                $newwithdraw['iban'] = $request->iban;
                // $newwithdraw['country'] = $request->acc_country;
                $newwithdraw['acc_name'] = $request->acc_name;
                $newwithdraw['address'] = $request->address;
                $newwithdraw['swift'] = $request->swift;
                $newwithdraw['reference'] = $request->reference;
                $newwithdraw['amount'] = $finalamount;
                $newwithdraw['fee'] = $fee;
                $newwithdraw['type'] = 'user';
                $newwithdraw->save();
                return response()->json(__('Withdraw Request Sent Successfully.'));
            } else {
                return response()->json(array('errors' => [0 => __('Insufficient Balance.')]));
            }
        }
        return response()->json(array('errors' => [0 => __('Please enter a valid amount.')]));
    }
}