<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Storage;

class ProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProfileService $profileService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profileService = new ProfileService();
    }

    /**
     * @test
     */
    public function create_a_profile(): void
    {
        $data = [
            'firstname' => 'Jean',
            'lastname' => 'Test',
            'image' => UploadedFile::fake()->image('avatar.png'),
            'status' => 'active',
        ];

        $profile = $this->profileService->createProfile($data);

        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertDatabaseHas('profiles', ['firstname' => 'Jean', 'lastname' => 'Test']);
    }

    /**
     * @test
     */
    public function updates_a_profile_with_new_image(): void
    {
        // Création d'un profil
        $profile = Profile::factory()->create([
            'firstname' => 'Jean',
            'lastname' => 'Test',
            'image' => 'images/old-image.jpg',
            'status' => 'inactive',
        ]);

        // Fausse nouvelle image pour la mise à jour
        $newImage = UploadedFile::fake()->image('new-image.jpg');

        // Données pour la mise à jour
        $data = [
            'firstname' => 'Updated Jean',
            'lastname' => 'Updated Test',
            'image' => $newImage,
            'status' => 'active',
        ];

        $updatedProfile = $this->profileService->updateProfile($profile, $data);

        // Vérifications
        $this->assertEquals('Updated Jean', $updatedProfile->firstname);
        $this->assertEquals('Updated Test', $updatedProfile->lastname);
        $this->assertEquals('active', $updatedProfile->status);

        // Vérifie que la nouvelle image a été stockée et que l'ancienne image a été supprimée
        Storage::disk('public')->assertExists('images/' . $newImage->hashName());
        Storage::disk('public')->assertMissing('images/old-image.jpg');
    }

    /**
     * @test
     */
    public function deletes_a_profile_and_its_image(): void
    {
        // Création d'un profil avec une image
        $profile = Profile::factory()->create([
            'firstname' => 'Jean',
            'lastname' => 'Test',
            'image' => 'images/fake-image.jpg',
            'status' => 'active',
        ]);
        Storage::disk('public')->put('images/fake-image.jpg', 'fake content');

        $this->profileService->deleteProfile($profile);

        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
        Storage::disk('public')->assertMissing('images/fake-image.jpg');
    }
}
