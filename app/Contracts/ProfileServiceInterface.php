<?php

namespace App\Contracts;

use App\Models\Profile;

interface ProfileServiceInterface
{
    /**
     * Créer un profil
     * @param array $data
     * @return Profile
     */
    public function createProfile(array $data): Profile;

    /**
     * Mettre a jour un profil existant
     * @param Profile $profile
     * @param array $data
     * @return Profile
     */
    public function updateProfile(Profile $profile, array $data): Profile;

    /**
     * Supprimer un profil
     * @param Profile $profile
     * @return bool
     */
    public function deleteProfile(Profile $profile): bool;
}
