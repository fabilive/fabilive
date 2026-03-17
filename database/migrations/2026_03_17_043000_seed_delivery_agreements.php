<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $agreements = [
            [
                'type' => 'Fabilive_Delivery_Individual_Agreement',
                'image' => 'assets/images/submerchantagreementrider/1773713000IndividualAgreement.pdf'
            ],
            [
                'type' => 'Fabilive_Delivery_Company_Agreement',
                'image' => 'assets/images/submerchantagreementrider/1773713000CompanyAgreement.pdf'
            ]
        ];

        foreach ($agreements as $agreement) {
            $exists = DB::table('agreements')->where('type', $agreement['type'])->exists();
            if (!$exists) {
                DB::table('agreements')->insert($agreement);
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
        // No revert
    }
};
