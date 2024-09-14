<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Admin;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_register(): void
    {
        $response = $this->postJson('/api/admin/register', [
            'email' => 'admin@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                    ->assertJsonStructure(['email']);
        $this->assertDatabaseHas('admins', ['email' => 'admin@test.com']);;
    }

    /**
     * @test
     */
    public function admin_can_login(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'token',
                ]);
    }
}