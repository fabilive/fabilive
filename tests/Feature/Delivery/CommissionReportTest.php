<?php

namespace Tests\Feature\Delivery;

use App\Models\DeliveryJob;
use App\Models\Order;
use App\Models\User;
use App\Models\Admin;
use App\Models\WalletLedger;
use App\Services\CommissionReportingService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CommissionReportTest extends TestCase
{
    use DatabaseTransactions;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        DB::statement("SET SESSION sql_mode=''");
        
        if (!Schema::hasColumn('orders', 'admin_verified')) {
            Schema::table('orders', function ($table) {
                $table->boolean('admin_verified')->default(false)->after('escrow_status');
            });
        }

        $this->service = new CommissionReportingService();
    }

    public function test_commission_aggregation_logic()
    {
        // Ensure a user exists for the wallet ledger FK
        $user = User::first() ?: User::factory()->create();
        
        // Create an order with commission
        $order = Order::factory()->create(['admin_verified' => true]);
        
        WalletLedger::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'amount' => 50,
            'type' => 'commission',
            'status' => 'completed'
        ]);

        // Create a delivery job with fees
        DeliveryJob::factory()->create([
            'order_id' => $order->id,
            'base_fee' => 10,
            'stopover_fee' => 5,
            'status' => 'delivered_verified'
        ]);

        // Call service
        $summary = $this->service->getCommissionSummary();

        // Assertions
        $this->assertGreaterThanOrEqual(50, $summary['total_admin_commission']);
        $this->assertGreaterThanOrEqual(15, $summary['total_delivery_fees']);
        $this->assertGreaterThanOrEqual(1, $summary['transaction_count']);
    }

    public function test_reconciliation_data_mapping()
    {
        $user = User::first() ?: User::factory()->create();

        $order = Order::factory()->create([
            'order_number' => 'TEST-RECON-001',
            'pay_amount' => 1000,
            'admin_verified' => true
        ]);

        WalletLedger::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'amount' => 100,
            'type' => 'commission',
            'status' => 'completed'
        ]);

        $data = $this->service->getReconciliationData();
        
        $match = $data->firstWhere('order_number', 'TEST-RECON-001');
        
        $this->assertNotNull($match);
        $this->assertEquals(100, $match['admin_commission']);
        $this->assertEquals(1000, $match['gross_amount']);
    }

    public function test_reconciliation_csv_export()
    {
        Order::factory()->count(2)->create(['admin_verified' => true]);

        // Create super admin (id=1 bypasses permissions middleware)
        Admin::where('id', 1)->delete();
        $admin = new Admin();
        $admin->id = 1;
        $admin->name = 'Super Admin';
        $admin->email = 'superadmin@test.com';
        $admin->phone = '1234567890';
        $admin->password = bcrypt('password');
        $admin->save();
        
        $response = $this->actingAs($admin, 'admin')
            ->get('/admin/reports/financial/export');

        $response->assertStatus(200);
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('Order Number', $content);
        $this->assertStringContainsString('Gross Amount', $content);
        $this->assertStringContainsString('Admin Commission', $content);
    }
}
