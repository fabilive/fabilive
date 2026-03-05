<?php

namespace App\Services;

use App\Models\DeliveryJob;
use App\Events\NewDeliveryJobAvailable;
use App\Models\User;

class DeliveryDispatchService
{
    /**
     * Notify all eligible riders in the service area about a new available job.
     */
    public function dispatchToRiders(DeliveryJob $job): void
    {
        // 1. Broadcast event via Ably
        broadcast(new NewDeliveryJobAvailable($job));

        // 2. You could also create DB-based notifications here for history
        // User::where('is_rider', 1)->where('service_area_id', $job->service_area_id)...
    }

    /**
     * Send a reminder to sellers who haven't marked their items as ready.
     */
    public function remindSellers(DeliveryJob $job): void
    {
        $pendingStops = $job->stops()->where('type', 'pickup')->where('status', 'pending')->get();
        
        foreach ($pendingStops as $stop) {
            // Send notification to $stop->seller
        }
    }
}
