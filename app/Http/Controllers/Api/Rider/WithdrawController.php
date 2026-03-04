<?php

namespace App\Http\Controllers\Api\Rider;

use App\{
    Models\Rider,
    Models\Withdraw,
    Models\Currency
};
use Illuminate\Http\Request;
use DB;
use App\Http\Resources\CustomWithdrawResource;

class WithdrawController
{
    protected $gs;
    protected $rider;

    public function __construct()
    {
        $this->gs = DB::table('generalsettings')->find(1);
        $this->rider = auth('rider-api')->user();
    }

    public function index()
    {
        $withdraws = Withdraw::where('user_id', $this->rider->id)->orderByDesc('id')->get();
        return response()->json(['data' => CustomWithdrawResource::collection($withdraws)]);
    }

    public function show($id)
    {
        $withdraw = Withdraw::where('id', $id)->where('user_id', $this->rider->id)->first();
        if (!$withdraw) {
            return response()->json(['message' => 'No record found'], 404);
        }
        return response()->json(['data' => new CustomWithdrawResource($withdraw)]);
    }

    public function store(Request $request)
    {
        $request->validate(['amount' => 'required', 'method' => 'required|in:Bank,Campay']);

        try {
            DB::beginTransaction();

            $from = Rider::lockForUpdate()->findOrFail($this->rider->id);
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
                    $from->save();

                    $newwithdraw = new Withdraw();
                    $newwithdraw['user_id'] = $from->id;
                    $newwithdraw['method'] = $request->method;
                    $newwithdraw['amount'] = number_format($finalamount, 2, '.', '');
                    $newwithdraw['fee'] = $fee;
                    $newwithdraw['type'] = 'rider';
                    $newwithdraw['reference'] = $request->reference;

                    if ($request->method == 'Campay') {
                        $newwithdraw['network'] = $request->network;
                        $newwithdraw['campay_acc_no'] = $request->campay_acc_no;
                        $newwithdraw['campay_acc_name'] = $request->campay_acc_name;
                    }

                    if ($request->method == 'Bank') {
                        $newwithdraw['iban'] = $request->iban;
                        $newwithdraw['acc_name'] = $request->acc_name;
                        $newwithdraw['address'] = $request->address;
                        $newwithdraw['swift'] = $request->swift;
                    }

                    $newwithdraw->save();

                    DB::commit();

                    return response()->json([
                        'status'  => true,
                        'message' => __('Withdraw Request Sent Successfully.'),
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json(['status' => true, 'message' => 'Insufficient Balance']);
                }
            }

            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Please enter a valid amount. ']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }
}