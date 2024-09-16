<?php

namespace App\Services;

use App\Contracts\ProfileServiceInterface;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class ProfileService implements ProfileServiceInterface
{
    /**
     * Create a profile
     * @param array $data
     * @return Profile
     */
    public function createProfile(array $data): Profile
    {
        // Save image in public repository if it exists
        if (isset($data['image'])) $data['image'] = $data['image']->store('images', 'public');
        
        // Create profile
        return Profile::create($data);
    }

    /**
     * Update an existing profile
     * @param Profile $profile
     * @param array $data
     * @return Profile
     */
    public function updateProfile(Profile $profile, array $data): Profile
    {
        if (isset($data['image'])) {
            // Delete old image if it exists
            Storage::disk('public')->delete($profile->image);
            // Save new image
            $data['image'] = $data['image']->store('images', 'public');
        }

        // Update profile
        $profile->update($data);

        return $profile;
    }

    /**
     * Delete a profile
     * @param Profile $profile
     * @return bool
     */
    public function deleteProfile(Profile $profile): bool
    {
        // Delete image linked to profile if it exists
        if ($profile->image) {
            Storage::disk('public')->delete($profile->image);
        }
        
        // Delete profile
        return $profile->delete();
    }

    /**
     * Get all active profiles
     * @return Collection $profiles
     */
    public function getActiveProfiles(): Collection
    {
        return Profile::where('status', 'active')->get();
    }

    /**
     * Get a profile by its id
     * @param int $id
     * @return Profile $profile
     */
    public function getProfileById(int $id): Profile
    {
        $profile = Profile::findOrFail($id);

        return $profile;
    }
}
