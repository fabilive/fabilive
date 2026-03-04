<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryFee;
class DeliveryFeeController extends Controller
{
    public function index()
    {
        $deliveryFees = DeliveryFee::orderBy('id', 'desc')->get();
        return view('admin.deliveryfee.index', compact('deliveryFees'));
    }
    public function create()
    {
        return view('admin.deliveryfee.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'weight'       => 'required|array',
            'start_range'  => 'required|array',
            'end_range'    => 'required|array',
            'fee'          => 'required|array',
        ]);
        if (count($request->weight) === 1) {
            DeliveryFee::create([
                'weight'      => $request->weight[0],
                'start_range' => $request->start_range[0],
                'end_range'   => $request->end_range[0],
                'fee'         => $request->fee[0],
            ]);
        } else {
            foreach ($request->weight as $key => $weight) {
                DeliveryFee::create([
                    'weight'      => $weight,
                    'start_range' => $request->start_range[$key],
                    'end_range'   => $request->end_range[$key],
                    'fee'         => $request->fee[$key],
                ]);
            }
        }
        return redirect()->back()->with('success', 'Delivery Fees Added Successfully');
    }
    public function edit($id)
{
    $deliveryFees = DeliveryFee::where('id', $id)->get(); // ✅ Collection milegi
    return view('admin.deliveryfee.edit', compact('deliveryFees', 'id'));
}
    public function update(Request $request, $id)
{
    $request->validate([
        'weight'       => 'nullable|array',
        'start_range'  => 'nullable|array',
        'end_range'    => 'nullable|array',
        'fee'          => 'nullable|array',
    ]);
    $deliveryFee = DeliveryFee::find($id);
    if (!$deliveryFee) {
        return redirect()->back()->with('error', 'Delivery Fee record not found');
    }
    $deliveryFee->update([
        'weight'      => $request->weight[0],
        'start_range' => $request->start_range[0],
        'end_range'   => $request->end_range[0],
        'fee'         => $request->fee[0],
    ]);
    if (count($request->weight) > 1) {
        for ($i = 1; $i < count($request->weight); $i++) {
            DeliveryFee::create([
                'weight'      => $request->weight[$i],
                'start_range' => $request->start_range[$i],
                'end_range'   => $request->end_range[$i],
                'fee'         => $request->fee[$i],
            ]);
        }
    }
    return redirect()
        ->route('admin-deliveryfee-index')
        ->with('success', 'Delivery Fee Updated & New Records Added Successfully');
}
    public function destroy($id)
    {
        $deliveryFee = DeliveryFee::findOrFail($id);
        $deliveryFee->delete();
        return redirect()->route('admin-deliveryfee-index')
                         ->with('success', 'Delivery Fee deleted successfully!');
    }
}

