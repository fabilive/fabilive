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
        $tables = [
            'withdraws' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('method')->nullable();
                $table->string('acc_email')->nullable();
                $table->string('acc_name')->nullable();
                $table->string('acc_number')->nullable();
                $table->decimal('amount', 15, 4)->default(0);
                $table->decimal('fee', 15, 4)->default(0);
                $table->string('status')->default('pending');
                $table->timestamps();
            },
            'coupons' => function (Blueprint $table) {
                $table->id();
                $table->string('code')->nullable();
                $table->string('type')->nullable(); // amount, percentage
                $table->decimal('price', 15, 4)->default(0);
                $table->integer('times')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('coupon_type')->nullable();
                $table->string('category')->nullable();
                $table->string('sub_category')->nullable();
                $table->string('child_category')->nullable();
            },
            'currencies' => function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('sign')->nullable();
                $table->decimal('value', 15, 4)->default(1);
                $table->boolean('is_default')->default(false);
            },
            'countries' => function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('phone_code')->nullable();
                $table->boolean('status')->default(true);
            },
            'states' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('country_id')->nullable();
                $table->string('name')->nullable();
                $table->boolean('status')->default(true);
            },
            'galleries' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('photo')->nullable();
            },
            'attributes' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('name')->nullable();
                $table->string('input_name')->nullable();
                $table->boolean('show_on_details')->default(true);
            },
            'attribute_options' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('attribute_id')->nullable();
                $table->string('name')->nullable();
            },
            'subscribers' => function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->timestamps();
            },
            'pages' => function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('slug')->unique();
                $table->text('details')->nullable();
                $table->string('meta_tag')->nullable();
                $table->text('meta_description')->nullable();
                $table->timestamps();
            },
            'faqs' => function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->text('details')->nullable();
            },
            'partners' => function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('photo')->nullable();
                $table->string('link')->nullable();
            },
            'social_links' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name')->nullable();
                $table->string('link')->nullable();
                $table->string('icon')->nullable();
            },
            'socialsettings' => function (Blueprint $table) {
                $table->id();
                $table->string('facebook_link')->nullable();
                $table->boolean('facebook_status')->default(false);
                $table->string('google_link')->nullable();
                $table->boolean('google_status')->default(false);
                $table->string('twitter_link')->nullable();
                $table->boolean('twitter_status')->default(false);
            },
            'fonts' => function (Blueprint $table) {
                $table->id();
                $table->string('font_family')->nullable();
                $table->boolean('is_default')->default(false);
            },
            'sliders' => function (Blueprint $table) {
                $table->id();
                $table->string('photo')->nullable();
                $table->string('title_text')->nullable();
                $table->string('link')->nullable();
                $table->integer('position')->default(0);
            },
            'arrival_sections' => function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('photo')->nullable();
                $table->boolean('status')->default(true);
            },
            'email_templates' => function (Blueprint $table) {
                $table->id();
                $table->string('email_type')->nullable();
                $table->string('email_subject')->nullable();
                $table->text('email_body')->nullable();
                $table->boolean('status')->default(true);
            },
            'blogs' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('title')->nullable();
                $table->string('slug')->unique();
                $table->text('details')->nullable();
                $table->string('photo')->nullable();
                $table->string('source')->nullable();
                $table->integer('views')->default(0);
                $table->timestamps();
            },
            'blog_categories' => function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->unique();
            },
            'comments' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->text('text')->nullable();
                $table->timestamps();
            },
            'replies' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('comment_id')->nullable();
                $table->text('text')->nullable();
                $table->timestamps();
            },
            'ratings' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->integer('rating')->default(0);
                $table->text('review')->nullable();
                $table->timestamps();
            },
            'reports' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('title')->nullable();
                $table->text('text')->nullable();
                $table->timestamps();
            },
            'verifications' => function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('text')->nullable();
                $table->string('status')->default('Pending');
                $table->timestamps();
            }
        ];

        foreach ($tables as $name => $definition) {
            if (!Schema::hasTable($name)) {
                Schema::create($name, $definition);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not dropping these as they are intended base tables
    }
};
