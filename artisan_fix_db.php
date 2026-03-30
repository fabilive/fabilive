<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

echo "Starting DB fixes...\n";

// 1. General Settings
$gs = DB::table('generalsettings')->first();
if (!$gs) {
    echo "General Settings missing. Trying to insert bare minimum defaults...\n";
    try {
        DB::table('generalsettings')->insert([
            'title' => 'Fabilive',
            'is_capcha' => 0,
            'verify_product' => 1,
            'is_smtp' => 0,
            'is_verification_email' => 0,
            'is_guest_checkout' => 0,
            'wholesell' => 0,
            'is_admin_loader' => 0,
            'rtl' => 0
        ]);
        echo "Inserted basic GS.\n";
    } catch (\Exception $e) {
        echo "Could not insert GS due to strict columns. Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "General Settings exist.\n";
}

// 2. Currency
$currency = Currency::where('is_default', 1)->first();
if (!$currency) {
    if (Currency::count() > 0) {
        $c = Currency::first();
        $c->is_default = 1;
        $c->save();
        echo "Set existing currency as default.\n";
    } else {
        Currency::create([
            'name' => 'CFA',
            'sign' => 'FCFA',
            'value' => 1,
            'is_default' => 1
        ]);
        echo "Created CFA default currency.\n";
    }
} else {
    echo "Default currency exists.\n";
}

// 3. Products missing slugs
try {
    $products = Product::whereNull('slug')->orWhere('slug', '')->get();
    $count = 0;
    foreach ($products as $p) {
        if (!$p->name) continue;
        $slug = Str::slug($p->name);
        // ensure unique
        if (Product::where('slug', $slug)->where('id', '!=', $p->id)->exists()) {
            $slug = $slug . '-' . rand(100, 999);
        }
        $p->slug = $slug;
        $p->save();
        $count++;
    }
    echo "Fixed slugs for $count products.\n";
} catch (\Exception $e) {
    echo "Error fixing slugs: " . $e->getMessage() . "\n";
}

// 4. Missing Product Photos
echo "Checking product photos...\n";
try {
    $imagesProducts = Product::whereNotNull('photo')->where('photo', '!=', '')->get();
    $missingCount = 0;
    foreach ($imagesProducts as $p) {
        if (!file_exists(public_path('assets/images/products/'.$p->photo))) {
            $p->photo = null;
            $p->save();
            $missingCount++;
        }
    }
    echo "Reset photo to null for $missingCount products missing actual files.\n";
} catch (\Exception $e) {
    echo "Could not wipe missing photos: " . $e->getMessage() . "\n";
}

// 5. FAQs
try {
    $faqCount = \App\Models\Faq::count();
    if ($faqCount == 0) {
        \App\Models\Faq::create([
            'title' => 'How can I register?',
            'details' => '<p>Click on the sign up button and enter your details.</p>'
        ]);
        \App\Models\Faq::create([
            'title' => 'How can I become a vendor?',
            'details' => '<p>Go to your dashboard and apply for vendor account.</p>'
        ]);
        echo "Inserted default FAQs.\n";
    } else {
        echo "FAQs already exist ($faqCount).\n";
    }
} catch (\Exception $e) {
    echo "Could not process FAQs: " . $e->getMessage() . "\n";
}

// 6. Vendor pagesettings faq true
try {
    $ps = DB::table('pagesettings')->first();
    if ($ps) {
        DB::table('pagesettings')->where('id', $ps->id)->update(['faq' => 1]);
        echo "Set FAQs to enabled in Pagesettings.\n";
    }
} catch (\Exception $e) {}

Cache::flush();
echo "Cache cleared. DB fixes complete!\n";
