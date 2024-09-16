<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Profile;

class ProfileViewTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    public function show_profiles_view(): void
    {
        Profile::factory()->create(['firstname' => 'Jean', 'lastname' => 'Test', 'status' => 'active']);

        $response = $this->get(route('profiles.index'));

        $response->assertStatus(200);
        $response->assertSee('Jean Test');
    }

    /**
     * @test
     */
    public function show_profile_view(): void
    {
        $profile = Profile::factory()->create(['firstname' => 'Jean', 'lastname' => 'Test']);

        $response = $this->get("/profiles/{$profile->id}");

        $response->assertStatus(200);
        $response->assertSee('Jean Test');
    }
}
