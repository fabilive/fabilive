<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Generalsetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Generalsetting::insert([
            'id' => 1,
            'is_capcha' => 0,
            'title' => 'Test',
        ]);
    }

    public function test_admin_login_generates_otp_and_redirects()
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson(route('admin.login.submit'), [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertSee(route('admin.otp.show'));

        $admin->refresh();
        $this->assertNotNull($admin->otp_code);
        $this->assertNotNull($admin->otp_expires_at);
    }

    public function test_admin_cannot_access_dashboard_without_otp_verification()
    {
        $admin = Admin::factory()->create();
        
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.otp.show'));
    }

    public function test_admin_can_verify_otp_and_access_dashboard()
    {
        $admin = Admin::factory()->create([
            'otp_code' => '123456',
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->post(route('admin.otp.verify'), [
            'otp_code' => '123456',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(session('admin_2fa_verified'));

        $admin->refresh();
        $this->assertNull($admin->otp_code);
        $this->assertNull($admin->otp_expires_at);
    }

    public function test_admin_logout_clears_session_and_otp_status()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin')
             ->withSession(['admin_2fa_verified' => true]);

        $response = $this->get(route('admin.logout'));

        $response->assertRedirect('/');
        $this->assertGuest('admin');
        $this->assertNull(session('admin_2fa_verified'));
    }
}
