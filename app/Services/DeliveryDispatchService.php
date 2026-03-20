<?php

namespace App\Services;

use App\Models\DeliveryJob;
use App\Events\NewDeliveryJobAvailable;
use App\Models\User;

class DeliveryDispatchService
{
    protected $notificationService;

    public function __construct(SmartNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Notify all eligible riders in the service area about a new available job.
     */
    public function dispatchToRiders(DeliveryJob $job): void
    {
        // 1. Broadcast event via Ably
        broadcast(new \App\Events\NewDeliveryJobAvailable($job));

        // 2. Create in-app notifications for all eligible riders in the service area
        $riders = \App\Models\Rider::where('status', 1)
            ->where('is_available', 1)
            ->get();

        foreach ($riders as $rider) {
            $this->notificationService->send($rider->id, 'new_delivery_job', [
                'title' => __('New Delivery Job Available'),
                'text' => __('Order #') . $job->order->order_number . __(' is ready for pickup in your area.'),
                'link' => route('rider-delivery-details', $job->id),
                'type' => 'delivery'
            ], 'in_app');
        }
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
