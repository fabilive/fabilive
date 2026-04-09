<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PriceHelper;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables as Datatables;

class UserTransactionController extends AdminBaseController
{
    //*** JSON Request
    public function transdatatables()
    {
        try {
            $datas = Transaction::orderBy('id', 'desc')->get();

            //--- Integrating This Collection Into Datatables
            return Datatables::of($datas)
                ->addColumn('name', function (Transaction $data) {
                    if ($data->user_id && $data->user && (isset($data->user['name']) || isset($data->user->name))) {
                        $name = '<a href="'.route('admin-user-show', $data->user_id).'" target="_blank">'.($data->user['name'] ?? $data->user->name).'</a>';
                    } else {
                        $name = '<span class="text-danger">'.__('Deleted Customer').' (ID: '.$data->user_id.')</span>';
                    }

                    return $name;
                })
                ->addColumn('date', function (Transaction $data) {
                    $date = date('Y-m-d', strtotime($data->created_at));

                    return $date;
                })
                ->editColumn('amount', function (Transaction $data) {
                    $val = (float) ($data->currency_value ?: 1);
                    $price = $data->amount * $val;
                    $price = PriceHelper::showOrderCurrencyPrice($price, $data->currency_sign);
                    if ($data->type == 'plus') {
                        $price = '+'.$price;
                    } else {
                        $price = '-'.$price;
                    }

                    return $price;
                })
                ->addColumn('action', function (Transaction $data) {
                    return '<div class="action-list">
                                                    <a href="javascript:;" data-href="'.route('admin-trans-show', $data->id).'" class="view" data-toggle="modal" data-target="#modal1"> 
                                                    <i class="fas fa-eye"></i> '.__('Details').'
                                                    </a>
                                                </div>';
                })
                ->rawColumns(['name', 'action'])
                ->toJson(); //--- Returning Json Data To Client Side
        } catch (\Exception $e) {
            return response()->json(['error' => 'Transaction DataTables failed: '.$e->getMessage()], 500);
        }
    }

    public function index()
    {
        return view('admin.trans.index');
    }

    //*** GET Request
    public function transhow($id)
    {
        $data = Transaction::find($id);

        return view('admin.trans.show', compact('data'));
    }
}
