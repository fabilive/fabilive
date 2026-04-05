<?php

namespace App\Http\Controllers\Rider;

use App\Models\Currency;
use App\Models\Rider;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawController extends RiderBaseController
{
    public function index()
    {
        $withdraws = Withdraw::where('user_id', '=', $this->rider->id)->latest('id')->get();
        $sign = \App\Models\Currency::where('is_default', '=', 1)->first();

        return view('rider.withdraw.index', compact('withdraws', 'sign'));
    }

    public function create()
    {
        $sign = \App\Models\Currency::where('is_default', '=', 1)->first();

        return view('rider.withdraw.withdraw', compact('sign'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $from = \App\Models\Rider::lockForUpdate()->findOrFail($this->rider->id);
            $withdrawcharge = $this->gs;
            $charge = $withdrawcharge->withdraw_fee;

            if ($request->amount > 0) {
                $amount = $request->amount;

                if ($from->balance >= $amount) {
                    $fee = (($withdrawcharge->withdraw_charge / 100) * $amount) + $charge;
                    $finalamount = $amount - $fee;

                    if ($finalamount < 0) {
                        DB::rollBack();

                        return response()->json(['errors' => [__('You can not withdraw this amount.')]]);
                    }

                    $from->balance -= $amount;
                    $from->update();

                    $newwithdraw = new Withdraw();
                    $newwithdraw['user_id'] = $this->rider->id;
                    $newwithdraw['method'] = $request->methods;
                    $newwithdraw['amount'] = number_format($finalamount, 2, '.', '');
                    $newwithdraw['fee'] = $fee;
                    $newwithdraw['reference'] = $request->reference;

                    if ($request->methods == 'Campay' || $request->methods == 'MTN Mobile Money' || $request->methods == 'Orange Money') {
                        if ($request->methods == 'MTN Mobile Money') {
                            $newwithdraw['network'] = 'MTN';
                        } elseif ($request->methods == 'Orange Money') {
                            $newwithdraw['network'] = 'Orange';
                        } else {
                            $newwithdraw['network'] = $request->network;
                        }
                        $newwithdraw['campay_acc_no'] = $request->campay_acc_no;
                        $newwithdraw['campay_acc_name'] = $request->campay_acc_name;
                    }

                    if ($request->methods == 'Bank') {
                        $newwithdraw['iban'] = $request->iban;
                        $newwithdraw['acc_name'] = $request->acc_name;
                        $newwithdraw['address'] = $request->address;
                        $newwithdraw['swift'] = $request->swift;
                    }

                    $newwithdraw->save();

                    DB::commit();

                    return response()->json(__('Withdraw Request Sent Successfully.'));
                } else {
                    DB::rollBack();

                    return response()->json(['errors' => [__('Insufficient Balance.')]]);
                }
            }
            DB::rollBack();

            return response()->json(['errors' => [__('Please enter a valid amount.')]]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['errors' => [$e->getMessage()]], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     $from = Rider::findOrFail($this->rider->id);
    //     $withdrawcharge = $this->gs;
    //     $charge = $withdrawcharge->withdraw_fee;
    //     if ($request->amount > 0) {
    //         $amount = $request->amount;
    //         if ($from->balance >= $amount) {
    //             $fee = (($withdrawcharge->withdraw_charge / 100) * $amount) + $charge;
    //             $finalamount = $amount - $fee;
    //             if($finalamount < 0){
    //               return response()->json(array('errors' => [0 => __('You can not withdraw this amount.')]));
    //             }
    //             if ($from->balance >= $finalamount) {
    //                 $finalamount = number_format((float)$finalamount, 2, '.', '');
    //                 $from->balance = $from->balance - $amount;
    //                 $from->update();
    //                 $newwithdraw = new Withdraw();
    //                 $newwithdraw['user_id'] = $this->rider->id;
    //                 $newwithdraw['method'] = $request->methods;
    //                 $newwithdraw['acc_email'] = $request->acc_email;
    //                 $newwithdraw['iban'] = $request->iban;
    //                 $newwithdraw['country'] = $request->acc_country;
    //                 $newwithdraw['acc_name'] = $request->acc_name;
    //                 $newwithdraw['address'] = $request->address;
    //                 $newwithdraw['swift'] = $request->swift;
    //                 $newwithdraw['reference'] = $request->reference;
    //                 $newwithdraw['amount'] = $finalamount;
    //                 $newwithdraw['fee'] = $fee;
    //                 $newwithdraw['type'] = 'rider';
    //                 $newwithdraw->save();
    //                 return response()->json(__('Withdraw Request Sent Successfully.'));
    //             } else {
    //                 return response()->json(array('errors' => [0 => __('Insufficient Balance.')]));
    //             }
    //         } else {
    //             return response()->json(array('errors' => [0 => __('Insufficient Balance.')]));
    //         }
    //     }
    //     return response()->json(array('errors' => [0 => __('Please enter a valid amount.')]));
    // }
}
