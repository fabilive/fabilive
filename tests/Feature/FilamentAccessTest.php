<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentAccessTest extends TestCase
{
    /** @test */
    public function guests_cannot_access_backoffice()
    {
        $response = $this->get('/backoffice');
        $response->assertRedirect('/backoffice/login');
    }

    /** @test */
    public function regular_users_cannot_access_backoffice()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/backoffice');
        // Since we use the 'admin' guard for the backoffice, actingAs(user) 
        // won't authenticate for the 'admin' guard by default if it's using a different provider.
        $response->assertRedirect('/backoffice/login');
    }

    /** @test */
    public function admins_can_access_backoffice()
    {
        $admin = Admin::first(); // Use existing admin from seeder/SQL
        if (!$admin) {
            $admin = Admin::create([
                'name' => 'Admin Test',
                'email' => 'admin_test@test.com',
                'password' => bcrypt('password'),
                'role_id' => 1,
            ]);
        }

        $response = $this->actingAs($admin, 'admin')->get('/backoffice');
        $response->dump();
        $response->assertStatus(200);
    }
}
