<?php

namespace App\Services;

use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Models\Rider;
use Illuminate\Support\Facades\Log;

class RiderOnboardingService
{
    /**
     * Check if a rider has all mandatory documents uploaded.
     */
    public function hasRequiredDocuments(Rider $rider): array
    {
        $missing = [];

        if (empty($rider->national_id_front_image)) {
            $missing[] = 'National ID (Front)';
        }

        if (empty($rider->national_id_back_image)) {
            $missing[] = 'National ID (Back)';
        }

        if (empty($rider->driver_license_individual)) {
            $missing[] = "Driver's License";
        }

        return $missing;
    }

    /**
     * Submit rider application for admin approval.
     */
    public function submitForApproval(Rider $rider): bool
    {
        $missing = $this->hasRequiredDocuments($rider);

        if (! empty($missing)) {
            throw new \Exception('Missing required documents: '.implode(', ', $missing));
        }

        $rider->onboarding_status = 'pending_approval';
        $rider->save();

        return true;
    }

    /**
     * Admin approves a rider application.
     */
    public function approve(Rider $rider, int $adminId = null): bool
    {
        if ($rider->onboarding_status === 'approved') {
            return false;
        }

        $rider->onboarding_status = 'approved';
        $rider->status = 1;
        $rider->approved_at = now();
        $rider->save();

        $this->sendApprovalEmail($rider);

        Log::info("Rider approved: rider_id={$rider->id}, admin_id={$adminId}");

        return true;
    }

    /**
     * Admin rejects a rider application with reason.
     */
    public function reject(Rider $rider, string $reason, int $adminId = null): bool
    {
        $rider->onboarding_status = 'rejected';
        $rider->rejection_reason = $reason;
        $rider->save();

        $this->sendRejectionEmail($rider, $reason);

        Log::info("Rider rejected: rider_id={$rider->id}, reason={$reason}, admin_id={$adminId}");

        return true;
    }

    private function sendApprovalEmail(Rider $rider): void
    {
        try {
            $gs = Generalsetting::first();
            $siteName = $gs->title ?? 'Fabilive';

            $data = [
                'to' => $rider->email,
                'subject' => "Your {$siteName} Delivery Agent Account is Active!",
                'body' => "
                    <h2>Welcome, {$rider->name}!</h2>
                    <p>Your delivery agent account on <strong>{$siteName}</strong> has been approved.</p>
                    <p>You can now accept delivery jobs in your service area.</p>
                    <p>Best regards,<br>The {$siteName} Team</p>
                ",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } catch (\Exception $e) {
            Log::error('Failed to send rider approval email: '.$e->getMessage());
        }
    }

    private function sendRejectionEmail(Rider $rider, string $reason): void
    {
        try {
            $gs = Generalsetting::first();
            $siteName = $gs->title ?? 'Fabilive';

            $data = [
                'to' => $rider->email,
                'subject' => "Your {$siteName} Delivery Agent Application Update",
                'body' => "
                    <h2>Hello, {$rider->name}</h2>
                    <p>Your delivery agent application was not approved:</p>
                    <blockquote>{$reason}</blockquote>
                    <p>You may resubmit after addressing the above.</p>
                    <p>Best regards,<br>The {$siteName} Team</p>
                ",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } catch (\Exception $e) {
            Log::error('Failed to send rider rejection email: '.$e->getMessage());
        }
    }
}
