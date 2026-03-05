<?php

namespace Tests\Feature\Delivery;

use App\Models\Admin;
use App\Models\DeliveryJob;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminVerifyIsIdempotentTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("SET SESSION sql_mode=''");
        
        if (!Schema::hasColumn('orders', 'admin_verified')) {
            Schema::table('orders', function ($table) {
                $table->boolean('admin_verified')->default(false)->after('escrow_status');
            });
        }
        if (!Schema::hasColumn('orders', 'payout_released_at')) {
            Schema::table('orders', function ($table) {
                $table->timestamp('payout_released_at')->nullable();
            });
        }
    }

    public function test_admin_verify_cannot_double_pay()
    {
        // Ensure super admin exists (id=1 bypasses permissions)
        // Use existing admin id=1 or create a new one
        $admin = Admin::find(1);
        if (!$admin) {
            $admin = new Admin();
            $admin->id = 1;
            $admin->name = 'Super Admin';
            $admin->email = 'admin_idempotent_' . time() . '@test.com';
            $admin->phone = '1234567890';
            $admin->password = bcrypt('password');
            $admin->save();
        }

        // Ensure Generalsetting exists
        $gs = \App\Models\Generalsetting::first();
        if (!$gs) {
            $gs = new \App\Models\Generalsetting();
        }
        $gs->percentage_commission = 10;
        $gs->fixed_commission = 0;
        $gs->rider_percentage_commission = 5;
        $gs->save();

        // Create order with required fields for escrow release
        $job = DeliveryJob::factory()->create(['status' => 'delivered_pending_verification']);
        
        $order = $job->order;
        $order->update([
            'status' => 'pending',
            'payment_status' => 'completed',
            'escrow_status' => 'pending',
            'admin_verified' => false,
        ]);

        // First attempt — should succeed
        $response1 = $this->actingAs($admin, 'admin')
            ->postJson("/admin/delivery/verify/{$job->id}");
        $response1->assertStatus(200);

        // Second attempt — should be rejected (already verified)
        $response2 = $this->actingAs($admin, 'admin')
            ->postJson("/admin/delivery/verify/{$job->id}");
            
        $this->assertTrue($response2->status() >= 400);
        $this->assertEquals('This delivery is already verified.', $response2->json('message'));
    }
}
