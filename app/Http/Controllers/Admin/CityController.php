<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CityController extends Controller
{
    public function managecity($city_id)
    {
        $state = State::findOrFail($city_id);

        return view('admin.country.state.city.index', compact('state'));
    }

    public function datatables($state_id)
    {
        $datas = City::with('state')->orderBy('id', 'desc')->where('state_id', $state_id)->get();

        return DataTables::of($datas)
            ->addColumn('action', function (City $data) use ($state_id) {
                return '<div class="action-list"><a data-href="'.route('admin-city-edit', $data->id).'" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="'.route('admin-city-delete', $data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->editColumn('state_id', function (City $data) {
                $state = $data->state->state;

                return $state;
            })
            ->addColumn('status', function (City $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';

                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'.route('admin-city-status', [$data->id, 1]).'" '.$s.'>Activated</option><option data-val="0" value="'.route('admin-city-status', [$data->id, 0]).'" '.$ns.'>Deactivated</option>/select></div>';
            })
            ->rawColumns(['action', 'status', 'state_id'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function create($state_id)
    {
        $state = State::findOrFail($state_id);

        return view('admin.country.state.city.create', compact('state'));
    }

    public function store(Request $request, $state_id)
    {
        $rules = [
            'city_name' => 'required|unique:cities,city_name',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        // Find state for country_id
        $stte = State::findOrFail($state_id);

        // Get latitude & longitude from OpenStreetMap API
        $response = Http::withHeaders([
            'User-Agent' => 'FabiLiveApp/1.0 (admin@fabilive.com)',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $request->city_name,
            'format' => 'json',
            'limit' => 1,
        ]);

        $data = $response->json();

        if (isset($data[0])) {
            $lat = $data[0]['lat'];
            $lng = $data[0]['lon'];
        } else {
            return response()->json(['errors' => [
                'city_name' => 'Could not find latitude and longitude for this city.',
            ]]);
        }

        // Save city with lat/lng
        $city = new City();
        $city->city_name = $request->city_name;
        $city->state_id = $state_id;
        $city->country_id = $stte->country_id;
        $city->latitude = $lat;
        $city->longitude = $lng;
        $city->status = 1;
        $city->save();

        $mgs = __('Data Added Successfully.');

        return response()->json($mgs);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'city_name' => 'required|unique:cities,city_name,'.$id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $city = City::findOrFail($id);

        // Get new latitude & longitude from API
        $response = Http::withHeaders([
            'User-Agent' => 'FabiLiveApp/1.0 (admin@fabilive.com)',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $request->city_name,
            'format' => 'json',
            'limit' => 1,
        ]);

        $data = $response->json();

        if (isset($data[0])) {
            $city->latitude = $data[0]['lat'];
            $city->longitude = $data[0]['lon'];
        } else {
            return response()->json(['errors' => [
                'city_name' => 'Could not find latitude and longitude for this city.',
            ]]);
        }

        $city->city_name = $request->city_name;
        $city->update();

        $mgs = __('Data Updated Successfully.');

        return response()->json($mgs);
    }

    // public function store(Request $request, $state_id)
    // {
    //     $rules = [
    //         'city_name'  => 'required|unique:cities,city_name',
    //     ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //         return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    //     }
    //     $stte=State::findOrFail($state_id);
    //     $state = new City();
    //     $state->city_name = $request->city_name;
    //     $state->state_id = $state_id;
    //     $state->country_id = $stte->country_id;
    //     $state->status = 1;
    //     $state->save();
    //     $mgs = __('Data Added Successfully.');
    //     return response()->json($mgs);
    // }
    // public function update(Request $request, $id)
    // {
    //     $rules = [
    //         'city_name'  => 'required|unique:cities,city_name,' . $id,
    //     ];
    //     $validator = Validator::make($request->all(), $rules);
    //     if ($validator->fails()) {
    //         return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    //     }
    //     $city = City::findOrFail($id);
    //     $city->city_name = $request->city_name;
    //     $city->update();
    //     $mgs = __('Data Updated Successfully.');
    //     return response()->json($mgs);
    // }

    public function status($id1, $id2)
    {
        $city = City::findOrFail($id1);
        $city->status = $id2;
        $city->update();
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);

        return view('admin.country.state.city.edit', compact('city'));
    }

    public function delete($id)
    {
        $state = State::findOrFail($id);
        $state->delete();
        $mgs = __('Data Deleted Successfully.');

        return response()->json($mgs);
    }

    public function loadCity(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->get();

        return response()->json($cities);
    }
}
