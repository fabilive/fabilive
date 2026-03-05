<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DistanceFee;

class DistanceFeeController extends Controller
{
    public function index()
    {
        $deliveryFees = DistanceFee::orderBy('id', 'desc')->get();
        return view('admin.distancefee.index', compact('deliveryFees'));
    }
    public function create()
    {
        return view('admin.distancefee.create');
    }
    public function store(Request $request)
{
    $request->validate([
        'distance_start_range' => 'required|array',
        'distance_end_range'   => 'required|array',
        'fee'                  => 'required|array',
    ]);
    foreach ($request->distance_start_range as $key => $startRange) {
        DistanceFee::create([
            'distance_start_range' => $startRange,
            'distance_end_range'   => $request->distance_end_range[$key],
            'fee'                  => $request->fee[$key],
        ]);
    }
    return redirect()->route('admin-distancefee-index')->with('success', 'Distance Fees Added Successfully');
}   
    public function edit($id)
{
    $deliveryFees = DistanceFee::where('id', $id)->get(); // ✅ Collection milegi
    return view('admin.distancefee.edit', compact('deliveryFees', 'id'));
}
//     public function update(Request $request, $id)
//     {
//     $request->validate([
//         'distance_start_range'  => 'nullable|array',
//         'distance_end_range'    => 'nullable|array',
//         'fee'          => 'nullable|array',
//     ]);
//     $deliveryFee = DistanceFee::find($id);
//     if (!$deliveryFee) {
//         return redirect()->back()->with('error', 'Delivery Fee record not found');
//     }
//     $deliveryFee->update([
//         'distance_start_range' => $request->distance_start_range[0],
//         'distance_end_range'   => $request->distance_end_range[0],
//         'fee'         => $request->fee[0],
//     ]);
//             DistanceFee::create([
//                 'distance_start_range' => $request->distance_start_range[$i],
//                 'distance_end_range'   => $request->distance_end_range[$i],
//                 'fee'         => $request->fee[$i],
//             ]);
//     return redirect()
//         ->route('admin-distancefee-index')
//         ->with('success', 'Delivery Fee Updated & New Records Added Successfully');
// }

    public function update(Request $request, $id)
{
    $request->validate([
        'distance_start_range' => 'required|array',
        'distance_end_range'   => 'required|array',
        'fee'                  => 'required|array',
    ]);

    // Fetch the existing record by ID
    $deliveryFee = DistanceFee::find($id);

    if (!$deliveryFee) {
        return redirect()->back()->with('error', 'Delivery Fee record not found');
    }

    // First update the existing record (first row)
    $deliveryFee->update([
        'distance_start_range' => $request->distance_start_range[0],
        'distance_end_range'   => $request->distance_end_range[0],
        'fee'                  => $request->fee[0],
    ]);

    // Insert new records if any more rows exist
    if (count($request->distance_start_range) > 1) {
        for ($i = 1; $i < count($request->distance_start_range); $i++) {
            DistanceFee::create([
                'distance_start_range' => $request->distance_start_range[$i],
                'distance_end_range'   => $request->distance_end_range[$i],
                'fee'                  => $request->fee[$i],
            ]);
        }
    }

    return redirect()
        ->route('admin-distancefee-index')
        ->with('success', 'Distance Fee Updated & New Records Added Successfully');
}


    public function destroy($id)
    {
        $deliveryFee = DistanceFee::findOrFail($id);
        $deliveryFee->delete();
        return redirect()->route('admin-distancefee-index')
                         ->with('success', 'Delivery Fee deleted successfully!');
    }
}

