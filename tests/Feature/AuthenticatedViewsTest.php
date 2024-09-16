<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use Laravel\Sanctum\Sanctum;

class AuthenticatedViewsTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    public function authenticated_user_can_access_protected_view(): void
    {
        Sanctum::actingAs(Admin::factory()->create());

        $response = $this->get('/profiles/1');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function unathenticated_user_cant_access_protected_view(): void
    {
        $response = $this->get('/profiles/1');

        $response->assertStatus(401);
    }
}
