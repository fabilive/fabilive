<?php

namespace App\Services;

use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SellerOnboardingService
{
    /**
     * Check if a user has all mandatory vendor documents uploaded.
     */
    public function hasRequiredDocuments(User $user): array
    {
        $missing = [];

        // TIN (taxpayer_card_copy) is mandatory
        if (empty($user->taxpayer_card_copy)) {
            $missing[] = 'TIN / Taxpayer Card';
        }

        // Government ID (at least national_id_front_image) is mandatory
        if (empty($user->national_id_front_image)) {
            $missing[] = 'Government ID (National ID Front)';
        }

        return $missing;
    }

    /**
     * Submit vendor application for admin approval.
     * Validates required docs, sets vendor_status to pending_approval.
     */
    public function submitForApproval(User $user): bool
    {
        $missing = $this->hasRequiredDocuments($user);

        if (! empty($missing)) {
            throw new \Exception('Missing required documents: '.implode(', ', $missing));
        }

        $user->vendor_status = 'pending_approval';
        $user->is_vendor = 1; // pending vendor
        $user->save();

        return true;
    }

    /**
     * Admin approves a vendor application.
     * Sets vendor_status to approved, is_vendor to 2, sends activation email.
     */
    public function approve(User $user, int $adminId = null): bool
    {
        if ($user->vendor_status === 'approved') {
            return false; // Already approved, idempotent
        }

        $user->vendor_status = 'approved';
        $user->is_vendor = 2; // Approved vendor
        $user->vendor_approved_at = now();
        $user->save();

        // Send activation email
        $this->sendApprovalEmail($user);

        Log::info("Vendor approved: user_id={$user->id}, admin_id={$adminId}");

        return true;
    }

    /**
     * Admin rejects a vendor application with reason.
     */
    public function reject(User $user, string $reason, int $adminId = null): bool
    {
        $user->vendor_status = 'rejected';
        $user->is_vendor = 0;
        $user->vendor_rejection_reason = $reason;
        $user->save();

        // Send rejection email
        $this->sendRejectionEmail($user, $reason);

        Log::info("Vendor rejected: user_id={$user->id}, reason={$reason}, admin_id={$adminId}");

        return true;
    }

    /**
     * Send activation email after approval.
     */
    private function sendApprovalEmail(User $user): void
    {
        try {
            $gs = Generalsetting::safeFirst();
            $siteName = $gs->title ?? 'Fabilive';

            $data = [
                'to' => $user->email,
                'subject' => "Your {$siteName} Seller Account is Now Active!",
                'body' => "
                    <h2>Congratulations, {$user->name}!</h2>
                    <p>Your <strong>{$siteName}</strong> seller account has been approved and is now active.</p>
                    <p>You can now:</p>
                    <ul>
                        <li>List physical and digital products</li>
                        <li>Manage your shop and inventory</li>
                        <li>Start receiving orders</li>
                    </ul>
                    <p>Welcome to the {$siteName} marketplace!</p>
                    <p>Best regards,<br>The {$siteName} Team</p>
                ",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } catch (\Exception $e) {
            Log::error('Failed to send vendor approval email: '.$e->getMessage());
        }
    }

    /**
     * Send rejection email with reason.
     */
    private function sendRejectionEmail(User $user, string $reason): void
    {
        try {
            $gs = Generalsetting::first();
            $siteName = $gs->title ?? 'Fabilive';

            $data = [
                'to' => $user->email,
                'subject' => "Your {$siteName} Seller Application Update",
                'body' => "
                    <h2>Hello, {$user->name}</h2>
                    <p>We have reviewed your seller application on <strong>{$siteName}</strong>.</p>
                    <p>Unfortunately, your application was not approved for the following reason:</p>
                    <blockquote>{$reason}</blockquote>
                    <p>You may resubmit your application after addressing the above concern.</p>
                    <p>Best regards,<br>The {$siteName} Team</p>
                ",
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } catch (\Exception $e) {
            Log::error('Failed to send vendor rejection email: '.$e->getMessage());
        }
    }
}
