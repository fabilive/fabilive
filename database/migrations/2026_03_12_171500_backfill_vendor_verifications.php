<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Verification;

class BackfillVendorVerifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $vendors = User::whereIn('is_vendor', [1, 2])->get();
        
        foreach ($vendors as $vendor) {
            $exists = Verification::where('user_id', $vendor->id)->exists();
            if (!$exists) {
                Verification::create([
                    'user_id' => $vendor->id,
                    'attachments' => implode(',', array_filter([$vendor->selfie_image, $vendor->national_id_front_image, $vendor->national_id_back_image])),
                    'status' => 'Pending',
                    'text' => 'Automatic backfill for existing vendor.'
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Not implemented to avoid accidental data loss
    }
}
