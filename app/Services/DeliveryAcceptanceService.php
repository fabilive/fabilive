<?php

namespace App\Services;

use App\Models\DeliveryJob;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class DeliveryAcceptanceService
{
    protected $jobService;

    public function __construct(DeliveryJobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Rider accepts an available job.
     * Uses lockForUpdate to ensure only one rider wins.
     */
    public function acceptJob(int $jobId, int $riderId): DeliveryJob
    {
        return DB::transaction(function () use ($jobId, $riderId) {
            // 1. Lock the job row for update
            $job = DeliveryJob::where('id', $jobId)
                ->where('status', 'available')
                ->lockForUpdate()
                ->first();

            if (!$job) {
                throw new Exception('Job is no longer available or already taken.');
            }

            // 2. Assign rider and update status
            $job->update([
                'assigned_rider_id' => $riderId,
                'status' => 'assigned',
                'accepted_at' => now()
            ]);

            // Sync main order status
            $job->order->update(['status' => 'rider accepted']);

            // 3. Log event
            $this->jobService->logEvent($job, 'rider', $riderId, 'job_accepted');

            // 4. Initialize temporary chat threads
            app(\App\Services\DeliveryChatService::class)->initializeThreads($job);

            // 5. Notify the Buyer
            app(\App\Services\SmartNotificationService::class)->send($job->buyer_id, 'delivery_rider_assigned', [
                'title' => __('Rider Assigned'),
                'text' => __('Rider ') . $job->rider->name . __(' has accepted your order #') . $job->order->order_number,
                'type' => 'order'
            ]);

            // 6. Notify the Seller(s)
            $sellerIds = $job->stops()->where('type', 'pickup')->pluck('seller_id')->unique();
            foreach ($sellerIds as $sellerId) {
                app(\App\Services\SmartNotificationService::class)->send($sellerId, 'rider_assigned_for_pickup', [
                    'title' => __('Rider for Pickup'),
                    'text' => __('Rider ') . $job->rider->name . __(' is coming to pick up order #') . $job->order->order_number,
                    'type' => 'order'
                ]);
            }

            return $job->fresh(['stops', 'rider']);
        });
    }
}
