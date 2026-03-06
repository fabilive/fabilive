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
