<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryJob;
use App\Services\DeliveryJobService;
use Illuminate\Http\Request;
use Exception;

class DeliveryJobController extends Controller
{
    protected $jobService;

    public function __construct(DeliveryJobService $jobService)
    {
        $this->middleware('auth:admin');
        $this->jobService = $jobService;
    }

    /**
     * Display a listing of delivery jobs.
     */
    public function index()
    {
        return view('admin.delivery_job.index');
    }

    /**
     * JSON request for datatables.
     */
    public function datatables()
    {
        $datas = DeliveryJob::latest('id')->get();
        
        return datatables()->of($datas)
            ->addColumn('order_number', function(DeliveryJob $data) {
                return $data->order->order_number ?? 'N/A';
            })
            ->addColumn('buyer', function(DeliveryJob $data) {
                return $data->buyer->name ?? 'N/A';
            })
            ->addColumn('rider', function(DeliveryJob $data) {
                return $data->rider->name ?? 'Unassigned';
            })
            ->addColumn('delivery_fee_total', function(DeliveryJob $data) {
                return 'XAF ' . number_format($data->delivery_fee_total, 2);
            })
            ->addColumn('rider_earnings', function(DeliveryJob $data) {
                return 'XAF ' . number_format($data->rider_earnings, 2);
            })
            ->addColumn('platform_delivery_commission', function(DeliveryJob $data) {
                return 'XAF ' . number_format($data->platform_delivery_commission, 2);
            })
            ->editColumn('status', function(DeliveryJob $data) {
                $class = match($data->status) {
                    'pending_readiness' => 'warning',
                    'assigned' => 'info',
                    'picked_up' => 'primary',
                    'delivered_pending_verification' => 'danger',
                    'delivered_verified' => 'success',
                    default => 'secondary'
                };
                return '<span class="badge badge-'.$class.'">'.ucwords(str_replace('_', ' ', $data->status)).'</span>';
            })
            ->addColumn('action', function(DeliveryJob $data) {
                return '<div class="action-list"><a href="' . route('admin-delivery-job-show', $data->id) . '" class="view"> <i class="fas fa-eye"></i> ' . __('Details') . '</a></div>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    /**
     * Display the specified delivery job.
     */
    public function show($id)
    {
        $data = DeliveryJob::findOrFail($id);
        return view('admin.delivery_job.show', compact('data'));
    }

    /**
     * Admin verifies proof and settles all parties.
     */
    public function verify(DeliveryJob $job)
    {
        try {
            if ($job->status !== 'delivered_pending_verification') {
                return response()->json(['message' => 'Job is not in a verifiable state.'], 400);
            }

            $this->jobService->verifyAndSettle($job);
            
            return response()->json(['message' => 'Delivery verified and payments settled.']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Verification failed: ' . $e->getMessage()], 500);
        }
    }
}
