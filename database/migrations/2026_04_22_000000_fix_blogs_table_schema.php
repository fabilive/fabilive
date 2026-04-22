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
        if (Schema::hasTable('blogs')) {
            Schema::table('blogs', function (Blueprint $table) {
                if (!Schema::hasColumn('blogs', 'status')) {
                    $table->integer('status')->default(1)->after('views');
                }
                if (!Schema::hasColumn('blogs', 'meta_tag')) {
                    $table->string('meta_tag')->nullable()->after('status');
                }
                if (!Schema::hasColumn('blogs', 'meta_description')) {
                    $table->text('meta_description')->nullable()->after('meta_tag');
                }
                if (!Schema::hasColumn('blogs', 'tags')) {
                    $table->string('tags')->nullable()->after('meta_description');
                }
                
                // Ensure timestamps exist and are consistent
                if (!Schema::hasColumn('blogs', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('blogs', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal to avoid data loss
    }
};
