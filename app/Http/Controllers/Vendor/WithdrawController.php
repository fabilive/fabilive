<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class WithdrawController extends VendorBaseController
{
    public function index()
    {
        $withdraws = Withdraw::where('user_id', '=', $this->user->id)->latest('id')->get();
        $sign = $this->curr;

        return view('vendor.withdraw.index', compact('withdraws', 'sign'));
    }

    public function create()
    {
        $sign = $this->curr;
        $actualBalance = $this->user->current_balance;

        return view('vendor.withdraw.create', compact('sign', 'actualBalance'));
    }

    public function store(Request $request)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $from = User::lockForUpdate()->find($this->user->id);
            $withdrawcharge = $this->gs;
            $charge = $withdrawcharge->withdraw_fee;

            if ($request->amount > 0) {
                $amount = $request->amount;
                if ($from->current_balance >= $amount) {
                    $fee = (($withdrawcharge->withdraw_charge / 100) * $amount) + $charge;
                    $finalamount = $amount - $fee;

                    if ($finalamount < 0) {
                        \Illuminate\Support\Facades\DB::rollBack();

                        return response()->json(['errors' => [0 => __('Withdraw amount is too low.')]]);
                    }

                    $finalamount = number_format((float) $finalamount, 2, '.', '');

                    $from->current_balance = $from->current_balance - $amount;
                    $from->update();

                    $newwithdraw = new Withdraw();
                    if ($request->methods == 'Campay' || $request->methods == 'MTN Mobile Money' || $request->methods == 'Orange Money') {
                        if ($request->methods == 'MTN Mobile Money') {
                            $newwithdraw['network'] = 'MTN';
                        } elseif ($request->methods == 'Orange Money') {
                            $newwithdraw['network'] = 'Orange';
                        } else {
                            if ($request->network) {
                                $newwithdraw['network'] = $request->network;
                            }
                        }
                        
                        if ($request->campay_acc_no) {
                            $newwithdraw['campay_acc_no'] = $request->campay_acc_no;
                        }
                        if ($request->campay_acc_name) {
                            $newwithdraw['campay_acc_name'] = $request->campay_acc_name;
                        }
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
                    $newwithdraw->save();

                    \App\Models\WalletLedger::create([
                        'user_id' => $from->id,
                        'amount' => $amount,
                        'type' => 'withdrawal_pending',
                        'reference' => 'WWD-'.$newwithdraw->id,
                        'status' => 'pending',
                        'details' => 'Vendor withdrawal request submitted.',
                    ]);

                    \Illuminate\Support\Facades\DB::table('payout_requests')->insert([
                        'user_id' => $from->id,
                        'role' => 'vendor',
                        'amount' => $amount,
                        'method' => $request->methods,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    \Illuminate\Support\Facades\DB::commit();

                    return response()->json(__('Withdraw Request Sent Successfully.'));
                } else {
                    return response()->json(['errors' => [0 => __('Insufficient Balance.')]]);
                }
            }

            return response()->json(['errors' => [0 => __('Please enter a valid amount.')]]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            return response()->json(['errors' => [0 => $e->getMessage()]], 500);
        }
    }
}
