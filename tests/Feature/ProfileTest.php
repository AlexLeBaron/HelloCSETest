<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Profile;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDataBase, withFaker;

    /**
     * @test
     */
    public function admin_can_create_a_profile(): void
    {
        //Simulate stockage environment
        Storage::fake('public');

        // Create an admin and login        
        $admin = Admin::factory()->create();
        $token = $admin->createToken('admin-token')->plainTextToken;

        // Create a fake image
        $file = UploadedFile::fake()->image('avatar.jpg');
 
        // Create a fake profile
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        
        // Profile creation request
        $response = $this->postJson('api/profiles', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'image' => $file,
            'status' => 'active',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(201);
        $this->assertDataBaseHas('profiles', ['firstname' => $firstName, 'lastname' => $lastName]);
        Storage::disk('public')->assertExists('images/' . $file->hashName());
    }

    /**
     * @test
     */
    public function unauthenticated_user_cannot_create_a_profile(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        $response = $this->postJson('/api/profiles', [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'image' => $file,
            'status' => 'active',
        ]);

        // Check if response is 401 Unauthorized
        $response->assertStatus(401);

        $this->assertDatabaseMissing('profiles', [
            'firstname' => $firstName,
            'lastname' => $lastName,
        ]);
    }

    /**
     * @test
     */
    public function returns_active_profiles_without_status_for_guests(): void
    {
        //Profiles creation
        Profile::factory()->create([
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'status' => 'active',
        ]);

        Profile::factory()->create([
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'status' => 'inactive',
        ]);
        
        $response = $this->getJson('/api/profiles/active');

        $response->assertStatus(200)
                    ->assertJsonCount(1) // We do not get the inactive profile
                    ->assertJsonMissing(['status']); // We do not get the status field
    }

    /**
     * @test
     */
    public function returns_active_profiles_with_status_for_authenticated_admin(): void
    {
        // Admin creation and authenticate
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        // Profiles creation
        Profile::factory()->create([
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'status' => 'active',
        ]);

        Profile::factory()->create([
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'status' => 'inactive',
        ]);

        // Getting active profile while authenticate
        $response = $this->getJson('/api/profiles/active');

        $response->assertStatus(200)
                    ->assertJsonCount(1) // Only one active profile
                    ->assertJsonFragment(['status' => 'active']); // Status field is not hidden
    }

    /**
     * @test
     */
    public function authenticated_admin_can_update_a_profile(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $profile = Profile::factory()->create([
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'status' => 'pending',
        ]);
        
        $response = $this->putJson("/api/profiles/{$profile->id}", [
            'firstname' => 'Updated',
            'status' => 'active',
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'firstname' => 'Updated',
            'status' => 'active',
        ]);
    }

    /**
     * @test
     */
    public function guest_cannot_update_a_profile(): void
    {
        $profile = Profile::factory()->create([
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'status' => 'pending',
        ]);

        $response = $this->putJson("/api/profiles/{$profile->id}", [
            'first_name' => 'Updated',
        ]);

        $response->assertStatus(401); // Unauthorized
    }

    /**
     * @test
     */
    public function authenticated_admin_can_delete_a_profile(): void
    {
        $admin = Admin::factory()->create();
        $token = $admin->createToken('admin-token')->plainTextToken;

        $profile = Profile::factory()->create([
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'status' => 'active',
        ]);

        $response = $this->deleteJson("/api/profiles/{$profile->id}", [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('profiles', [
            'id' => $profile->id
        ]);
    }

    /**
     * @test
     */
    public function guest_cannot_delete_a_profile(): void
    {
        $profile = Profile::factory()->create([
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'status' => 'active',
        ]);

        $response = $this->deleteJson("/api/profiles/{$profile->id}");

        $response->assertStatus(401); // Unauthorized
    }

    /**
     * @test
     */
    public function rejects_invalid_profile_data(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $response =  $this->postJson('api/profiles', [
            'firstname' => '',
            'lastname' => '',
            'image' => 'not-an-image',
            'status' => 'unknown',
        ]);

        $response->assertStatus(422)
                    ->assertJsonValidationErrors(['firstname', 'lastname', 'image', 'status']);
    }

    /**
     * @test
     */
    public function reject_invalid_update_data(): void
    {
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        $profile = Profile::factory()->create();

        $response = $this->putJson("/api/profiles/{$profile->id}", [
            'status' => 'invalid-status',
        ]);

        $response->assertStatus(422)
                    ->assertJsonValidationErrors(['status']);
    }
}
