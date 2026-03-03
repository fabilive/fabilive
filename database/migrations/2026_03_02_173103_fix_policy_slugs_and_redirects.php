<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Fix malformed slugs in the 'pages' table
        DB::table('pages')->where('slug', 'anti scam')->update(['slug' => 'anti-scam']);
        DB::table('pages')->where('slug', 'return policy')->update(['slug' => 'return-policy']);
        DB::table('pages')->where('slug', 'refund policy')->update(['slug' => 'refund-policy']);

        // 2. Create a simple 'redirects' table for 301 handling if we want to handle it via DB
        // For now, we will handle the most critical ones via the migration logic:
        // Note: Spaces in DB slugs are often stored as %20 in URLs, but DB might have actual spaces.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pages')->where('slug', 'anti-scam')->update(['slug' => 'anti scam']);
        DB::table('pages')->where('slug', 'return-policy')->update(['slug' => 'return policy']);
        DB::table('pages')->where('slug', 'refund-policy')->update(['slug' => 'refund policy']);
    }
};
