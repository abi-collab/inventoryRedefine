<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\CreatesUsers;
use Tests\TestCase;

class AuthRoleTest extends TestCase
{
    use CreatesUsers;

    public function test_standard_user_cannot_sync_now(): void
    {
        $user = $this->makeUser(['user_role' => 2]);

        $this->withHeaders($this->authHeaders($user))
            ->postJson('/api/sync/now')
            ->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        $admin = $this->makeAdmin();

        $this->withHeaders($this->authHeaders($admin))
            ->postJson('/api/users', [
                'name' => 'New Cashier',
                'username' => 'cashier1',
                'email' => 'cashier1@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'user_role' => 2,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('users', ['username' => 'cashier1', 'user_role' => 2]);
    }

    public function test_standard_user_cannot_list_users(): void
    {
        $user = $this->makeUser(['user_role' => 2]);

        $this->withHeaders($this->authHeaders($user))
            ->getJson('/api/users')
            ->assertStatus(403);
    }
}
