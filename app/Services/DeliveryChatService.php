<?php

namespace App\Services;

use App\Models\DeliveryJob;
use App\Models\DeliveryChatThread;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class DeliveryChatService
{
    /**
     * Initialize temporary chat threads for a delivery job.
     */
    public function initializeThreads(DeliveryJob $job): void
    {
        // 1. Thread between Rider and Buyer
        DeliveryChatThread::firstOrCreate([
            'delivery_job_id' => $job->id,
            'thread_type' => 'rider_buyer',
            'buyer_id' => $job->buyer_id,
            'rider_id' => $job->assigned_rider_id
        ]);

        // 2. Threads between Rider and each unique Seller
        $sellerIds = $job->stops()->where('type', 'pickup')->pluck('seller_id')->unique();
        foreach ($sellerIds as $sellerId) {
            DeliveryChatThread::firstOrCreate([
                'delivery_job_id' => $job->id,
                'thread_type' => 'rider_seller',
                'seller_id' => $sellerId,
                'rider_id' => $job->assigned_rider_id
            ]);
        }
    }

    /**
     * Auto-hide threads for users after job completion.
     */
    public function closeChatThreads(DeliveryJob $job): void
    {
        DeliveryChatThread::where('delivery_job_id', $job->id)
            ->update(['hidden_at' => now()]);
    }

    /**
     * Archive (hide) threads for jobs completed more than 24 hours ago.
     */
    public function archiveExpiredThreads(): int
    {
        return DeliveryChatThread::whereNull('hidden_at')
            ->whereHas('deliveryJob', function($query) {
                $query->whereIn('status', ['delivered', 'cancelled', 'returned'])
                      ->where('updated_at', '<', now()->subHours(24));
            })
            ->update(['hidden_at' => now()]);
    }

    /**
     * Check if a thread is visible to a user.
     */
    public function isThreadVisible(DeliveryChatThread $thread): bool
    {
        return is_null($thread->hidden_at);
    }
}
