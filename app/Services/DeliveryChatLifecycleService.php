<?php

namespace App\Services;

use App\Models\DeliveryJob;
use App\Models\DeliveryChatThread;
use Illuminate\Support\Facades\Log;

class DeliveryChatLifecycleService
{
    /**
     * Close all chat threads associated with a delivery job.
     */
    public function closeDeliveryChats(int $jobId, string $reason): void
    {
        Log::info("Closing delivery chats for job #{$jobId}. Reason: {$reason}");

        DeliveryChatThread::where('delivery_job_id', $jobId)
            ->whereNull('hidden_at')
            ->update([
                'hidden_at' => now()
            ]);
    }

    /**
     * Check if a thread is currently active and visible to non-admins.
     */
    public function isChatActive(DeliveryChatThread $thread): bool
    {
        return is_null($thread->hidden_at);
    }
}
