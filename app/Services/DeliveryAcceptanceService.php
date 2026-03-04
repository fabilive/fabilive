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

            // 3. Log event
            $this->jobService->logEvent($job, 'rider', $riderId, 'job_accepted');

            // 4. Initialize temporary chat threads
            app(\App\Services\DeliveryChatService::class)->initializeThreads($job);

            return $job->fresh(['stops', 'rider']);
        });
    }
}
