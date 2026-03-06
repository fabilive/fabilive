<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryJob;
use App\Services\EscrowReleaseService;
use App\Services\DeliveryJobService;
use App\Services\DeliveryChatLifecycleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class DeliveryVerificationController extends Controller
{
    protected $escrowService;
    protected $jobService;
    protected $chatLifecycleService;

    public function __construct(
        EscrowReleaseService $escrowService,
        DeliveryJobService $jobService,
        DeliveryChatLifecycleService $chatLifecycleService
    ) {
        $this->escrowService = $escrowService;
        $this->jobService = $jobService;
        $this->chatLifecycleService = $chatLifecycleService;
    }

    /**
     * Admin verifies a delivery and releases funds.
     */
    public function verify(Request $request, DeliveryJob $job)
    {
        try {
            if ($job->status === 'delivered_verified') {
                return response()->json(['message' => 'This delivery is already verified.'], 400);
            }

            if ($job->status !== 'delivered_pending_verification') {
                return response()->json(['message' => 'Delivery is not in a pending verification state.'], 400);
            }

            // Use the unified settlement service
            $this->jobService->verifyAndSettle($job);

            return response()->json(['status' => true, 'message' => 'Delivery verified and funds released successfully.']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
