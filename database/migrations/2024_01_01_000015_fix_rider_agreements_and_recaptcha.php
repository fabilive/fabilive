<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create agreements table if missing
        if (!Schema::hasTable('agreements')) {
            Schema::create('agreements', function (Blueprint $table) {
                $table->id();
                $table->string('type')->nullable(); // rider_agreement, company_agreement, etc.
                $table->text('details')->nullable();
                $table->string('file')->nullable();
                $table->timestamps();
            });
        }

        // 2. Seed default agreements
        if (DB::table('agreements')->count() == 0) {
            DB::table('agreements')->insert([
                [
                    'type' => 'rider_agreement',
                    'details' => 'Delivery Agent Agreement',
                    'file' => 'submerchantagreementrider/1773713000IndividualAgreement.pdf'
                ],
                [
                    'type' => 'company_agreement',
                    'details' => 'Delivery Company Agreement',
                    'file' => 'submerchantagreementrider/1773713000CompanyAgreement.pdf'
                ]
            ]);
        }

        // 3. Enable Recaptcha and ensure keys exist (even if dummy)
        DB::table('generalsettings')->where('id', 1)->update([
            'is_capcha' => 1,
            'capcha_site_key' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', // Public test keys
            'capcha_secret_key' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFojJ4WifJWeG',
        ]);
    }

    public function down(): void {}
};
