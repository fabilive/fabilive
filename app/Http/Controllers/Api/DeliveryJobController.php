<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryJob;
use App\Models\DeliveryJobStop;
use App\Models\Order;
use App\Models\VendorOrder;
use App\Services\DeliveryJobService;
use App\Services\DeliveryDispatchService;
use App\Services\DeliveryAcceptanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class DeliveryJobController extends Controller
{
    protected $jobService;
    protected $dispatchService;
    protected $acceptanceService;

    public function __construct(
        DeliveryJobService $jobService,
        DeliveryDispatchService $dispatchService,
        DeliveryAcceptanceService $acceptanceService
    ) {
        $this->jobService = $jobService;
        $this->dispatchService = $dispatchService;
        $this->acceptanceService = $acceptanceService;
    }

    /**
     * Seller marks their part of an order as ready for pickup.
     */
    public function sellerReady(Request $request, Order $order)
    {
        $sellerId = Auth::id(); // Assuming seller is authenticated
        
        // 1. Find the delivery job and the specific stop for this seller
        $job = DeliveryJob::where('order_id', $order->id)->first();
        if (!$job) {
            // If job doesn't exist yet, create it
            $job = $this->jobService->createJobFromOrder($order);
        }

        $stop = $job->stops()->where('type', 'pickup')->where('seller_id', $sellerId)->first();
        if (!$stop) {
            return response()->json(['message' => 'Stop not found for this seller.'], 404);
        }

        if ($stop->status !== 'pending') {
            return response()->json(['message' => 'Items already marked as ready or picked up.'], 400);
        }

        // 2. Mark stop as ready
        $stop->update([
            'status' => 'ready',
            'ready_at' => now()
        ]);

        $this->jobService->logEvent($job, 'seller', $sellerId, 'seller_marked_ready');

        // 3. Dispatch rule: notify riders if this is the FIRST seller ready
        if ($job->status === 'pending_readiness') {
            $this->jobService->transitionStatus($job, 'available', 'system', null, ['trigger' => 'first_seller_ready']);
            $this->dispatchService->dispatchToRiders($job);
            // Also notify other sellers to hurry up
            $this->dispatchService->remindSellers($job);
        }

        return response()->json(['message' => 'Marked as ready for pickup. Riders are being notified.', 'job' => $job->load('stops')]);
    }

    /**
     * List available jobs for riders.
     */
    public function availableJobs()
    {
        $rider = Auth::user();
        // Limit to "Limbe only" logic (simplified: matching rider service area)
        $jobs = DeliveryJob::where('status', 'available')
            ->where('service_area_id', $rider->service_area_id)
            ->with(['order', 'stops'])
            ->get();

        return response()->json($jobs);
    }

    /**
     * Rider accepts a job.
     */
    public function accept(DeliveryJob $job)
    {
        try {
            $updatedJob = $this->acceptanceService->acceptJob($job->id, Auth::id());
            return response()->json(['message' => 'Job accepted.', 'job' => $updatedJob]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Rider marks a pickup stop as arrived/picked up.
     */
    public function updateStop(Request $request, DeliveryJob $job, DeliveryJobStop $stop)
    {
        if ($job->assigned_rider_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $status = $request->input('status'); // arrived, picked_up
        if (!in_array($status, ['arrived', 'picked_up'])) {
            return response()->json(['message' => 'Invalid status.'], 400);
        }

        $stop->update([
            'status' => $status,
            $status . '_at' => now()
        ]);

        $this->jobService->logEvent($job, 'rider', Auth::id(), 'stop_' . $status, ['stop_id' => $stop->id]);

        // If all pickups are done, transition job to picked_up
        $pendingPickups = $job->stops()->where('type', 'pickup')->where('status', '!=', 'picked_up')->count();
        if ($pendingPickups === 0 && $job->status !== 'picked_up') {
            $this->jobService->transitionStatus($job, 'picked_up', 'rider', Auth::id());
        }

        return response()->json(['message' => 'Stop updated.', 'job' => $job->load('stops')]);
    }

    /**
     * Rider marks job as delivered with proof.
     */
    public function markDelivered(Request $request, DeliveryJob $job)
    {
        if ($job->assigned_rider_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'proof_photo' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('proof_photo')) {
            $path = $request->file('proof_photo')->store('delivery_proofs', 'public');
            $job->update([
                'proof_photo' => $path,
                'proof_uploaded_at' => now()
            ]);
        }

        $this->jobService->transitionStatus($job, 'delivered_pending_verification', 'rider', Auth::id());

        return response()->json(['message' => 'Delivered successfully. Awaiting admin verification.']);
    }

    /**
     * Cancel a delivery job.
     */
    public function cancel(DeliveryJob $job)
    {
        $this->jobService->transitionStatus($job, 'cancelled', 'admin', Auth::id());
        app(\App\Services\DeliveryChatLifecycleService::class)->closeDeliveryChats($job->id, 'cancelled');

        return response()->json(['message' => 'Delivery job cancelled.']);
    }

    /**
     * Mark a delivery job as returned.
     */
    public function returnJob(DeliveryJob $job)
    {
        $this->jobService->transitionStatus($job, 'returned', 'admin', Auth::id());
        app(\App\Services\DeliveryChatLifecycleService::class)->closeDeliveryChats($job->id, 'returned');

        return response()->json(['message' => 'Delivery job marked as returned.']);
    }

    /**
     * Unified tracking for buyer.
     */
    public function tracking(Order $order)
    {
        $job = DeliveryJob::where('order_id', $order->id)->with(['stops', 'rider', 'events'])->first();
        if (!$job) {
            return response()->json(['status' => 'Preparing your items']);
        }

        return response()->json($job);
    }
}
