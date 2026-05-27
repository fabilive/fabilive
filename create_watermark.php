<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Intervention\Image\Facades\Image as Image;

$logoPath = public_path('assets/images/fabilive_logo_transparent.png');
$watermarkPath = public_path('assets/front/images/watermark.png');

if (file_exists($logoPath)) {
    // Make watermark 150px wide, keeping aspect ratio
    $img = Image::make($logoPath)->resize(150, null, function ($constraint) {
        $constraint->aspectRatio();
    });
    
    // Reduce opacity to make it a subtle watermark
    // $img->opacity(50); // CAUSES GREY BACKGROUND BUG IN GD
    
    $img->save($watermarkPath);
    echo "Watermark created successfully at $watermarkPath\n";
} else {
    echo "Logo not found at $logoPath\n";
}
