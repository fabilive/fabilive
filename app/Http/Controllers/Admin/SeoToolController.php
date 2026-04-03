<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductClick;
use App\Models\Seotool;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SeoToolController extends AdminBaseController
{
    public function analytics()
    {
        $tool = Seotool::find(1);

        return view('admin.seotool.googleanalytics', compact('tool'));
    }

    public function analyticsupdate(Request $request)
    {
        $tool = Seotool::findOrFail(1);

        $input = $request->all();

        if ($request->has('meta_keys')) {
            $input['meta_keys'] = implode(',', $request->meta_keys);
        }

        $tool->update($input);

        cache()->forget('seotools');
        $msg = __('Data Updated Successfully.');

        return response()->json($msg);
    }

    public function keywords()
    {
        $tool = Seotool::find(1);

        return view('admin.seotool.meta-keywords', compact('tool'));
    }

    public function popular($id)
    {
        $expDate = Carbon::now()->subDays($id);
        $productss = ProductClick::whereDate('date', '>', $expDate)->get()->groupBy('product_id');
        $val = $id;

        return view('admin.seotool.popular', compact('val', 'productss'));
    }
}
