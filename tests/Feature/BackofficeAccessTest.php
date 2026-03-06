<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BackofficeAccessTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthenticated_user_cannot_access_backoffice()
    {
        $response = $this->get('/backoffice');
        $response->assertRedirect('/backoffice/login');
    }

    public function test_regular_user_cannot_access_backoffice()
    {
        $user = User::first();

        if (!$user) {
            $this->markTestSkipped('No users in database to test with.');
        }

        $response = $this->actingAs($user, 'web')->get('/backoffice');

        // Should redirect to admin login since web guard != admin guard
        $response->assertRedirect('/backoffice/login');
    }

    public function test_admin_can_access_backoffice()
    {
        $admin = Admin::first();

        if (!$admin) {
            $this->markTestSkipped('No admins in database to test with.');
        }

        $response = $this->actingAs($admin, 'admin')->get('/backoffice');

        $response->assertSuccessful();
    }
}
