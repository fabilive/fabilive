<?php
namespace App\Http\Controllers\Admin;
use App\Models\ServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator;
use Datatables;
class ServiceAreaController extends AdminBaseController
{
    public function datatables()
    {
        $datas = ServiceArea::latest('id')->get();
        return Datatables::of($datas)
            ->addColumn('action', function(ServiceArea $data) {
                return '<div class="action-list">
                            <a data-href="' . route('admin-servicearea-edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1">
                                <i class="fas fa-edit"></i>' . __('Edit') . '
                            </a>
                            <a href="javascript:;" data-href="' . route('admin-servicearea-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>';
            })
            ->toJson();
    }
    public function index()
    {
        return view('admin.servicearea.index');
    }
    public function create()
    {
        return view('admin.servicearea.create');
    }
    public function store(Request $request)
{
    $rules = [
        'location' => 'required|unique:service_areas'
    ];
    $customs = [
        'location.unique' => __('This location has already been taken.')
    ];
    $validator = Validator::make($request->all(), $rules, $customs);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()]);
    }
    $location = $request->location;
    $response = Http::withHeaders([
        'User-Agent' => 'FabiLiveApp/1.0 (admin@fabilive.com)'
    ])->get('https://nominatim.openstreetmap.org/search', [
        'q' => $location,
        'format' => 'json',
        'limit' => 1
    ]);
    $data = $response->json();
    if (isset($data[0])) {
        $lat = $data[0]['lat'];
        $lng = $data[0]['lon'];
    } else {
        return response()->json(['errors' => [
            'location' => 'Could not find latitude and longitude for this location.'
        ]]);
    }
    $serviceArea = new ServiceArea();
    $serviceArea->fill([
        'location' => $location,
        'latitude' => $lat,
        'longitude' => $lng
    ])->save();
    return response()->json(__('New Data Added Successfully.'));
}
    public function edit($id)
    {
        $data = ServiceArea::findOrFail($id);
        return view('admin.servicearea.edit', compact('data'));
    }
    public function update(Request $request, $id)
{
    $rules = [
        'location' => 'required|unique:service_areas,location,' . $id
    ];
    $customs = [
        'location.unique' => __('This location has already been taken.')
    ];
    $validator = Validator::make($request->all(), $rules, $customs);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()]);
    }
    $location = $request->location;
    $response = Http::withHeaders([
        'User-Agent' => 'FabiLiveApp/1.0 (admin@fabilive.com)'
    ])->get('https://nominatim.openstreetmap.org/search', [
        'q' => $location,
        'format' => 'json',
        'limit' => 1
    ]);
    $data = $response->json();
    if (isset($data[0])) {
        $lat = $data[0]['lat'];
        $lng = $data[0]['lon'];
    } else {
        return response()->json(['errors' => [
            'location' => 'Could not find latitude and longitude for this location.'
        ]]);
    }
    $serviceArea = ServiceArea::findOrFail($id);
    $serviceArea->update([
        'location' => $location,
        'latitude' => $lat,
        'longitude' => $lng
    ]);
    return response()->json(__('Data Updated Successfully.'));
}
    public function destroy($id)
    {
        $data = ServiceArea::findOrFail($id);
        $data->delete();

        return response()->json(__('Data Deleted Successfully.'));
    }
}