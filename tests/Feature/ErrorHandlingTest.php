<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use Laravel\Sanctum\Sanctum;

class ErrorHandlingTest extends TestCase
{
    /**
     * @test
     */
    public function returns_a_422_when_register_validation_fails()
    {
        $response = $this->postJson('/api/admin/register', [
            'name' => 'Jean Admin',
            'email' => 'admin@example.com',
            'password' => '',
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => ['password'],
                ]);
    }

    /**
     * @test
     */
    public function returns_a_401_when_authentication_fails_for_login()
    {
        $admin = Admin::factory()->create();

        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ]);
    }

    /**
     * @test
     */
    public function returns_a_404_when_updating_non_existent_profile()
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);  // Authenticate as admin

        $response = $this->putJson('/api/profiles/9999', [
            'first_name' => 'Jean',
            'last_name' => 'Test',
            'status' => 'active',
        ]);

        $response->assertStatus(404)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Not found',
                ]);
    }

    /**
     * @test
     */
    public function returns_a_404_for_non_existent_route()
    {
        $response = $this->getJson('/api/non-existent-route');

        $response->assertStatus(404)
                    ->assertJson([
                        'status' => 'error',
                        'message' => 'Route not found',
                    ]);
    }
}
