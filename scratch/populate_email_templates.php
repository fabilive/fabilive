<?php

use Illuminate\Support\Facades\DB;

$templates = [
    [
        'email_type' => 'new_registration',
        'email_subject' => 'Welcome to Fabilive - Your Account is Ready!',
        'email_body' => 'Hello {customer_name},<br><br>Welcome to Fabilive! Your account has been successfully created. You can now start shopping or selling on our platform.<br><br>Best regards,<br>The Fabilive Team',
        'status' => 1
    ],
    [
        'email_type' => 'order_confirmed',
        'email_subject' => 'Order Confirmation - {order_number}',
        'email_body' => 'Hello {customer_name},<br><br>Your order #{order_number} has been successfully placed. The total amount is {order_amount}. We will notify you once your order is dispatched.<br><br>Thank you for shopping with us!',
        'status' => 1
    ],
    [
        'email_type' => 'order_shipped',
        'email_subject' => 'Your Order #{order_number} is on the way!',
        'email_body' => 'Hello {customer_name},<br><br>Great news! Your order #{order_number} has been dispatched and is on its way to you.<br><br>Best regards,<br>The Fabilive Team',
        'status' => 1
    ],
    [
        'email_type' => 'order_completed',
        'email_subject' => 'Order Delivered - #{order_number}',
        'email_body' => 'Hello {customer_name},<br><br>Your order #{order_number} has been delivered. We hope you enjoy your purchase!<br><br>Best regards,<br>The Fabilive Team',
        'status' => 1
    ],
    [
        'email_type' => 'vendor_verification',
        'email_subject' => 'Vendor Verification Update',
        'email_body' => 'Hello {customer_name},<br><br>Your vendor verification status has been updated. Please log in to your dashboard to see the details.<br><br>Best regards,<br>The Fabilive Team',
        'status' => 1
    ],
    [
        'email_type' => 'dispute_received',
        'email_subject' => 'New Dispute Notification - #{order_number}',
        'email_body' => 'Hello,<br><br>A new dispute has been opened for order #{order_number}. Please review the details in your dashboard.<br><br>Best regards,<br>The Fabilive Team',
        'status' => 1
    ],
    [
        'email_type' => 'contact_form',
        'email_subject' => 'We received your message',
        'email_body' => 'Hello {customer_name},<br><br>Thank you for contacting us. We have received your message and will get back to you as soon as possible.<br><br>Best regards,<br>The Fabilive Team',
        'status' => 1
    ],
];

echo "Populating email templates...\n";

foreach ($templates as $template) {
    $exists = DB::table('email_templates')->where('email_type', $template['email_type'])->first();
    
    if (!$exists) {
        DB::table('email_templates')->insert($template);
        echo "Created template: {$template['email_type']}\n";
    } else {
        echo "Template already exists: {$template['email_type']} (Skipping)\n";
    }
}

echo "Done.\n";
