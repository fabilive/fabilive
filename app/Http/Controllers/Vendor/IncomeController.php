<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = VendorOrder::with('order')
            ->where('user_id', Auth::user()->id)
            ->where('status', 'completed');

        if ($request->start_date && $request->end_date) {
            $start_date = Carbon::parse($request->start_date);
            $end_date = Carbon::parse($request->end_date);
            $query->whereDate('created_at', '>=', $start_date)
                  ->whereDate('created_at', '<=', $end_date);
        } else {
            $start_date = null;
            $end_date = null;
        }

        $datas = $query->get();
        $total = $datas->count() > 0
            ? ($datas->first()->order->currency_sign ?? 'CFA') . number_format($datas->sum('price'), 0)
            : 0;

        return view('vendor.earning', [
            'datas' => $datas,
            'total' => $total,
            'start_date' => $start_date ?? '',
            'end_date' => $end_date ?? '',
        ]);
    }
}
