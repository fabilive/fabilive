<?php
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    $gs = Generalsetting::first();
    echo "Logo: " . $gs->logo . "\n";
    echo "Footer Logo: " . $gs->footer_logo . "\n";
    echo "Is Capcha: " . ($gs->is_capcha ?? 'N/A') . "\n";
    echo "Capcha Key: " . ($gs->capcha_key ?? 'N/A') . "\n";
    
    $blogCols = Schema::getColumnListing('blogs');
    echo "Blog Columns: " . implode(', ', $blogCols) . "\n";
    
    $pagesettings = DB::table('pagesettings')->first();
    echo "Blog enabled: " . ($pagesettings->blog ?? 'N/A') . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
