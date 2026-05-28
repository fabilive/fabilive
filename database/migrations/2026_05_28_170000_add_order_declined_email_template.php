<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing order_declined email template
        $exists = DB::table('email_templates')
            ->where('email_type', 'order_declined')
            ->first();

        if (!$exists) {
            DB::table('email_templates')->insert([
                'email_type'    => 'order_declined',
                'email_subject' => 'Your Order #{order_number} Has Been Declined',
                'email_body'    => 'Hello {customer_name},<br><br>We regret to inform you that your order <strong>#{order_number}</strong> has been declined.<br><br>If you were charged, a refund will be processed. Please contact our support team if you have any questions.<br><br>Best regards,<br>The Fabilive Team',
                'status'        => 1,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('email_templates')
            ->where('email_type', 'order_declined')
            ->delete();
    }
};
