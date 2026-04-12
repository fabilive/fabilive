<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

// Target Page Titles and possible slugs
$targets = [
    ['title' => 'Refunds policy', 'slugs' => ['refund-policy', 'refunds-policy', 'refund policy']],
    ['title' => 'Delivery Rates Policy', 'slugs' => ['delivery-rates-policy', 'delivery-rates', 'delivery rates policy']],
    ['title' => 'Limitation of Liability', 'slugs' => ['limitation-of-liability', 'limitation of liability']],
    ['title' => 'Dispute Resolution', 'slugs' => ['dispute-resolution', 'dispute resolution']],
    ['title' => 'Policy Updates', 'slugs' => ['policy-updates', 'policy updates']],
];

echo "Updating policy visibility...\n";

foreach ($targets as $target) {
    $updated = DB::table('pages')
        ->whereIn('slug', $target['slugs'])
        ->orWhere('title', 'like', '%' . $target['title'] . '%')
        ->update([
            'header' => 1,
            'footer' => 1
        ]);
    
    if ($updated) {
        echo "Updated visibility for: {$target['title']}\n";
    } else {
        echo "Could not find page for: {$target['title']}\n";
    }
}

// Clear relevant cache
Cache::forget('global_pages');
echo "Cache 'global_pages' cleared.\n";

echo "Done.\n";
