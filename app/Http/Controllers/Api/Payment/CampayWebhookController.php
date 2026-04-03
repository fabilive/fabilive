<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletLedger;
use App\Models\WebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $eventId = $payload['reference'] ?? null;
        $status = $payload['status'] ?? null;

        if (! $eventId) {
            return response()->json(['message' => 'Missing reference'], 400);
        }

        // 1. Idempotency Check
        $event = WebhookEvent::where('event_id', $eventId)->first();
        if ($event) {
            // Already processed
            return response()->json(['message' => 'Event already processed'], 200);
        }

        // 2. Log exact payload
        $webhookEvent = WebhookEvent::create([
            'event_id' => $eventId,
            'payload' => $payload,
            'status' => 'pending',
        ]);

        if (strtolower($status) === 'successful') {
            try {
                DB::transaction(function () use ($payload, $webhookEvent, $eventId) {
                    // Attempt to find Order by txnid
                    $order = Order::where('txnid', $eventId)->where('payment_status', '!=', 'Completed')->first();
                    if ($order) {
                        $this->processOrderPayment($order, $payload);
                    } else {
                        // Check if it's a deposit
                        $deposit = Deposit::where('txnid', $eventId)->where('status', 0)->first();
                        if ($deposit) {
                            $this->processDepositPayment($deposit, $payload);
                        }
                    }

                    $webhookEvent->update([
                        'status' => 'processed',
                        'processed_at' => now(),
                    ]);
                }, 5);
            } catch (\Exception $e) {
                Log::error('Campay Webhook Error: '.$e->getMessage());
                $webhookEvent->update(['status' => 'failed']);

                return response()->json(['message' => 'Processing failed'], 500);
            }
        }

        return response()->json(['message' => 'Webhook received'], 200);
    }

    protected function processOrderPayment($order, $payload)
    {
        $order->payment_status = 'Completed';
        $order->update();

        // Ledger: Escrow Hold
        WalletLedger::create([
            'user_id' => $order->user_id ?? 0,
            'amount' => $order->pay_amount,
            'type' => 'escrow_hold',
            'order_id' => $order->id,
            'reference' => $order->txnid,
            'status' => 'completed',
            'details' => 'Payment held in escrow via Campay Webhook',
        ]);

        $order->tracks()->create(['title' => 'Paid', 'text' => 'Payment confirmed via Campay.']);
    }

    protected function processDepositPayment($deposit, $payload)
    {
        $deposit->status = 1;
        $deposit->update();

        $user = User::lockForUpdate()->find($deposit->user_id);
        if ($user) {
            $user->balance += $deposit->amount;
            $user->save();

            WalletLedger::create([
                'user_id' => $user->id,
                'amount' => $deposit->amount,
                'type' => 'deposit',
                'reference' => $deposit->txnid,
                'status' => 'completed',
                'details' => 'Wallet deposit via Campay',
            ]);
        }
    }
}
