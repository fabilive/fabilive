<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManageAgreement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AgreeMentController extends Controller
{
    //--- Datatables
    public function datatables()
    {
        $datas = ManageAgreement::latest('id')->get();

        return Datatables::of($datas)
            ->addColumn('type', function (ManageAgreement $data) {
                return '<div>'.ucwords($data->type).'</div>';
            })
            ->addColumn('pdf', function (ManageAgreement $data) {
                if ($data->image) {
                    return '<a href="'.asset($data->image).'" target="_blank">View PDF</a>';
                }

                return 'N/A';
            })
            ->addColumn('action', function (ManageAgreement $data) {
                return '<div class="action-list">
                    <a href="'.route('admin-agreement-edit', $data->id).'">
                        <i class="fas fa-edit"></i> '.__('Edit').'
                    </a>
                    <a href="javascript:;" data-href="'.route('admin-agreement-delete', $data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['type', 'pdf', 'action'])
            ->toJson();
    }

    //--- Index
    public function index()
    {
        return view('admin.agreement.index');
    }

    //--- Create
    public function create()
    {
        return view('admin.agreement.create');
    }

    //--- Store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'image' => 'required|mimes:pdf|max:5120',
        ]);

        // Check if agreement of this type already exists
        $exists = ManageAgreement::where('type', $validated['type'])->first();
        if ($exists) {
            return response()->json([
                'error' => __('An agreement of this type already exists.'),
            ], 422);
        }

        // Upload PDF
        $pdfPath = null;
        if ($request->hasFile('image')) {
            $pdf = $request->file('image');
            $pdfName = time().'_'.$pdf->getClientOriginalName();
            $pdf->move(public_path('agreements'), $pdfName);
            $pdfPath = 'agreements/'.$pdfName;
        }

        // Store new agreement
        $agreement = new ManageAgreement();
        $agreement->type = $validated['type'];
        $agreement->image = $pdfPath;
        $agreement->save();

        $msg = __('New Agreement Added Successfully.').'<a href="'.route('admin-agreement-index').'">'.__('View Agreement List').'</a>';

        return response()->json($msg);
    }

    //--- Edit
    public function edit($id)
    {
        $data = ManageAgreement::findOrFail($id);

        return view('admin.agreement.edit', compact('data'));
    }

    //--- Update
    public function update(Request $request, $id)
    {
        $data = ManageAgreement::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string',
            'image' => 'nullable|mimes:pdf|max:5120',
        ]);

        // Check if another agreement with the same type exists
        $exists = ManageAgreement::where('type', $validated['type'])
            ->where('id', '!=', $id)
            ->first();
        if ($exists) {
            return response()->json([
                'error' => __('An agreement of this type already exists.'),
            ], 422);
        }

        // Upload new PDF if provided
        if ($request->hasFile('image')) {
            $pdf = $request->file('image');
            $pdfName = time().'_'.$pdf->getClientOriginalName();
            $pdf->move(public_path('agreements'), $pdfName);
            $data->image = 'agreements/'.$pdfName;
        }

        $data->type = $validated['type'];
        $data->save();

        $msg = __('Agreement Updated Successfully.').'<a href="'.route('admin-agreement-index').'">'.__('View Agreement List').'</a>';

        return response()->json($msg);
    }

    //--- Delete
    public function destroy($id)
    {
        $data = ManageAgreement::findOrFail($id);
        $data->delete();

        $msg = __('Agreement Deleted Successfully.');

        return response()->json($msg);
    }
}
