<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rider;
use App\Models\Withdraw;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;

class RiderController extends AdminBaseController
{
    public function datatables()
    {
        try {
            $datas = Rider::with('orders')->latest('id')->get();

            return Datatables::of($datas)
                ->addColumn('total_delivery', function (Rider $data) {
                    return $data->orders->count();
                })
                ->addColumn('action', function (Rider $data) {
                    $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                    $s = $data->status == 0 ? 'selected' : '';
                    $ns = $data->status == 1 ? 'selected' : '';
                    $ban = '<select class="process select droplinks '.$class.'">'.
                        '<option data-val="0" value="'.route('admin-rider-ban', ['id1' => $data->id, 'id2' => 0]).'" '.$s.'>'.__('Block').'</option>'.
                        '<option data-val="1" value="'.route('admin-rider-ban', ['id1' => $data->id, 'id2' => 1]).'" '.$ns.'>'.__('UnBlock').'</option></select>';

                    return '<div class="action-list">
                                <a href="javascript:;" class="send" data-email="'.$data->email.'" data-toggle="modal" data-target="#vendorform">
                                <i class="fas fa-envelope"></i> '.__('Send').'
                                </a>'
                        .$ban.
                        '
                        <a href="'.route('admin-rider-show', $data->id).'" >
                            <i class="fas fa-eye"></i> '.__('Details').'
                        </a>

                        <a href="javascript:;" data-href="'.route('admin-rider-delete', $data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete">
                                <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>';
                })
                ->addColumn('rider_status', function (Rider $data) {
                    $current = $data->rider_status;

                    return '
          <select class="rider-status-change niceSelect" data-id="'.$data->id.'">
            <option value="pending" '.($current == 'pending' ? 'selected' : '').'>Pending</option>
            <option value="accepted" '.($current == 'accepted' ? 'selected' : '').'>Accepted</option>
            <option value="declined" '.($current == 'declined' ? 'selected' : '').'>Declined</option>
          </select>';
                })
                ->rawColumns(['action', 'total_delivery', 'rider_status'])
                ->toJson(); //--- Returning Json Data To Client Side
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Runtime Exception: '.$e->getMessage().' in '.basename($e->getFile()).':'.$e->getLine()]);
        }
    }

    public function index()
    {
        return view('admin.rider.index');
    }

    public function show($id)
    {
        $data = Rider::findOrFail($id);

        // dd($data);
        return view('admin.rider.show', compact('data'));
    }

    public function statusUpdate(Request $request)
    {
        try {
            $rider = Rider::findOrFail($request->id);
            $rider->rider_status = $request->status;
            if ($request->status == 'accepted') {
                $rider->status = 1;
                $rider->onboarding_status = 'approved';
                $rider->approved_at = Carbon::now();
                $rider->email_verify = 'Yes';
                $rider->email_verified = 'Yes';
            } elseif ($request->status == 'declined') {
                $rider->status = 0;
                $rider->onboarding_status = 'rejected';
            }
            $rider->save();

            return response()->json(['success' => true, 'message' => __('Status updated successfully')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function withdraws()
    {
        return view('admin.rider.withdraws');
    }

    public function ban($id1, $id2)
    {
        $user = Rider::findOrFail($id1);
        $user->status = $id2;
        $user->update();
    }

    public function destroy($id)
    {
        $user = Rider::findOrFail($id);

        return true;
        $user->delete();
        $msg = __('Data Deleted Successfully.');

        return response()->json($msg);
    }

    public function withdrawdatatables()
    {
        $datas = Withdraw::where('type', '=', 'rider')->latest('id')->get();

        return Datatables::of($datas)
            ->addColumn('email', function (Withdraw $data) {
                $email = $data->rider->email;

                return $email;
            })
            ->addColumn('phone', function (Withdraw $data) {
                $phone = $data->rider->phone;

                return $phone;
            })
            ->editColumn('status', function (Withdraw $data) {
                $status = ucfirst($data->status);

                return $status;
            })
            ->editColumn('amount', function (Withdraw $data) {
                $sign = $this->curr;
                $amount = $data->amount * $sign->value;

                return \PriceHelper::showAdminCurrencyPrice($amount);
            })
            ->addColumn('action', function (Withdraw $data) {
                $action = '<div class="action-list"><a data-href="'.route('admin-withdraw-rider-show', $data->id).'" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i> '.__('Details').'</a>';
                if ($data->status == 'pending') {
                    $action .= '<a data-href="'.route('admin-withdraw-rider-accept', $data->id).'" data-toggle="modal" data-target="#status-modal1"> <i class="fas fa-check"></i> '.__('Accept').'</a><a data-href="'.route('admin-withdraw-rider-reject', $data->id).'" data-toggle="modal" data-target="#status-modal"> <i class="fas fa-trash-alt"></i> '.__('Reject').'</a>';
                }
                $action .= '</div>';

                return $action;
            })
            ->rawColumns(['name', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function withdrawdetails($id)
    {
        $sign = $this->curr;
        $withdraw = Withdraw::findOrFail($id);

        return view('admin.rider.withdraw-details', compact('withdraw', 'sign'));
    }

    public function accept($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $data['status'] = 'completed';
        $withdraw->update($data);
        $msg = __('Withdraw Accepted Successfully.');

        return response()->json($msg);
    }

    public function reject($id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $withdraw = Withdraw::lockForUpdate()->findOrFail($id);
            if ($withdraw->status == 'rejected' || $withdraw->status == 'completed') {
                return response()->json('Already processed');
            }

            $account = Rider::lockForUpdate()->findOrFail($withdraw->rider->id);
            $account->balance = $account->balance + $withdraw->amount + $withdraw->fee;
            $account->update();

            $data['status'] = 'rejected';
            $withdraw->update($data);

            // Log reversal in ledger
            \App\Models\WalletLedger::create([
                'user_id' => $account->id,
                'amount' => $withdraw->amount + $withdraw->fee,
                'type' => 'withdrawal_reversal',
                'reference' => 'WDR-'.$withdraw->id,
                'status' => 'completed',
                'details' => 'Rider withdrawal request rejected by admin. Funds returned to balance.',
            ]);

            \Illuminate\Support\Facades\DB::commit();

            $msg = __('Withdraw Rejected Successfully.');

            return response()->json($msg);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            return response()->json($e->getMessage());
        }
    }
}
