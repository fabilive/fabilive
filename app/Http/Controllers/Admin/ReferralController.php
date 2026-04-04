<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReferralUsage;
use Yajra\DataTables\Facades\DataTables as Datatables;

class ReferralController extends AdminBaseController
{
    public function datatables()
    {
        $datas = ReferralUsage::latest('id')->get();

        return Datatables::of($datas)
            ->addColumn('referrer', function (ReferralUsage $data) {
                return $data->referralCode->user->name ?? $data->referralCode->rider->name ?? 'System';
            })
            ->addColumn('referred', function (ReferralUsage $data) {
                return $data->referredUser->name ?? $data->referredRider->name ?? 'Unknown';
            })
            ->editColumn('referred_role', function (ReferralUsage $data) {
                return strtoupper($data->referred_role);
            })
            ->editColumn('referrer_bonus', function (ReferralUsage $data) {
                return 'XAF '.number_format($data->referrer_bonus, 2);
            })
            ->editColumn('referred_bonus', function (ReferralUsage $data) {
                return 'XAF '.number_format($data->referred_bonus, 2);
            })
            ->editColumn('status', function (ReferralUsage $data) {
                $class = $data->status == 'completed' ? 'drop-success' : 'drop-warning';

                return '<span class="badge '.$class.'">'.ucfirst($data->status).'</span>';
            })
            ->rawColumns(['status'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.referral.index');
    }
}
