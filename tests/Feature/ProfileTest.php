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
        //Simulation de l'environnement de stockage
        Storage::fake('public');

        // Creation d'un admin et login        
        $admin = Admin::factory()->create();
        $token = $admin->createToken('admin-token')->plainTextToken;

        // Creation d'un faux fichier image
        $file = UploadedFile::fake()->image('avatar.jpg');
 
        // Creation d'un fake profile
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        
        // Creation d'un profil
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

        // Verifie que la reponse est 401 Unauthorized
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
        //Creation de profils
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
                    ->assertJsonCount(1) // On ne recupere pas le profil inactif
                    ->assertJsonMissing(['status']); // Le champ status est exclu
    }

    /**
     * @test
     */
    public function returns_active_profiles_with_status_for_authenticated_admin(): void
    {
        // Creation d'un administrateur et authentication
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        // Creation de profils
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

        // Recuperation des profils actifs avec authentication
        $response = $this->getJson('/api/profiles/active');

        $response->assertStatus(200)
                    ->assertJsonCount(1) // Un seul profil actif
                    ->assertJsonFragment(['status' => 'active']); // Le champ status est accessible
    }

    /**
     * @test
     */
    public function authenticated_admin_can_update_a_profile(): void
    {
        //Creation d'un administrateur et authentication
        $admin = Admin::factory()->create();
        Sanctum::actingAs($admin);

        // Creation d'un profil
        $profile = Profile::factory()->create([
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'status' => 'pending',
        ]);
        
        // Requete de mise a jour avec authentication
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
        // Creation d'un profil
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
        //Creation d'un administrateur et authentication
        $admin = Admin::factory()->create();
        $token = $admin->createToken('admin-token')->plainTextToken;

        // Creation d'un profil
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
        // Creation d'un profil
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
        //Creation d'un administrateur
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
        //Creation d'un administrateur
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
