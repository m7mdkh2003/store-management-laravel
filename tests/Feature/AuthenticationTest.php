<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_accounts_are_created_as_regular_users(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Customer One',
            'email' => 'customer1@example.com',
            'password' => 'Strong123',
            'password_confirmation' => 'Strong123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'customer1@example.com',
            'role' => User::ROLE_USER,
        ]);
    }

    public function test_regular_users_cannot_open_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();
    }

    public function test_admins_can_open_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk();
    }
}
