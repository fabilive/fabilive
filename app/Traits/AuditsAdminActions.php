<?php

namespace App\Traits;

use Spatie\Activitylog\Models\Activity;

trait AuditsAdminActions
{
    /**
     * Log an admin action for auditing purposes.
     * 
     * @param string $action The name of the action (e.g., 'Verify Delivery')
     * @param mixed $subject The model instance the action was performed on
     * @param array $metadata Additional context for the log
     */
    public function auditAdminAction(string $action, $subject = null, array $metadata = []): void
    {
        activity()
            ->performedOn($subject)
            ->causedBy(auth('admin')->user())
            ->withProperties($metadata)
            ->event($action)
            ->log($action);
    }
}
