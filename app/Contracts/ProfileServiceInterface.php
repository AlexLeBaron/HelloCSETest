<?php

namespace App\Contracts;

use App\Models\Profile;

interface ProfileServiceInterface
{
    /**
     * Create a profile
     * @param array $data
     * @return Profile
     */
    public function createProfile(array $data): Profile;

    /**
     * Update an existing profile
     * @param Profile $profile
     * @param array $data
     * @return Profile
     */
    public function updateProfile(Profile $profile, array $data): Profile;

    /**
     * Delete a profile
     * @param Profile $profile
     * @return bool
     */
    public function deleteProfile(Profile $profile): bool;
}
