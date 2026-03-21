<?php

$files = glob('app/Http/Controllers/Payment/Subscription/*.php');
$files[] = 'app/Http/Controllers/User/SubscriptionController.php';
$files[] = 'app/Http/Controllers/Vendor/VoguepayController.php';

foreach($files as $file) {
    if(!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Replace with Auth::user() check for SubscriptionController's payreturn
    if (strpos($file, 'SubscriptionController.php') !== false) {
        $content = str_replace(
            "__('Vendor Account Activated Successfully')",
            "Auth::user()->is_vendor == 2 ? __('Vendor Account Activated Successfully') : __('Vendor Application Submitted Successfully. Please wait for admin approval.')",
            $content
        );
    }
    
    // For payment controllers (they usually have $user variable)
    // Be careful to use the exact string
    $replacements = [
        "/'success'\s*,\s*__\('Vendor Account Activated Successfully'\)/" => "'success', strpos(get_class(\$this), 'SubscriptionController') !== false && !isset(\$user) ? (Auth::user()->is_vendor == 2 ? __('Vendor Account Activated Successfully') : __('Vendor Application Submitted Successfully. Please wait for admin approval.')) : (\$user->is_vendor == 2 ? __('Vendor Account Activated Successfully') : __('Vendor Application Submitted Successfully. Please wait for admin approval.'))",
        "/'success'\s*,\s*'Vendor Account Activated Successfully'/" => "'success', isset(\$user) && \$user->is_vendor == 2 ? 'Vendor Account Activated Successfully' : 'Vendor Application Submitted Successfully. Please wait for admin approval.'",
        "/'success'\s*,\s*__\('Subscription Activated Successfully'\)/" => "'success', isset(\$user) && \$user->is_vendor == 2 ? __('Subscription Activated Successfully') : __('Vendor Application Submitted Successfully. Please wait for admin approval.')",
        "/'Your Vendor Account Activated Successfully\. Please Login to your account and build your own shop\.'/" => "isset(\$user) && \$user->is_vendor == 2 ? 'Your Vendor Account Activated Successfully. Please Login to your account and build your own shop.' : 'Your Vendor Application Submitted Successfully. Please wait for admin approval.'"
    ];

    foreach ($replacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    file_put_contents($file, $content);
}
echo "Done replacing messages.";
