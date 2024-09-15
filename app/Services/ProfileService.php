<?php

namespace App\Services;

use App\Contracts\ProfileServiceInterface;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileService implements ProfileServiceInterface
{
    /**
     * Créer un profil
     * @param array $data
     * @return Profile
     */
    public function createProfile(array $data): Profile
    {
        // Enregistrer l'image dans le répertoire public si existante
        if (isset($data['image'])) $data['image'] = $data['image']->store('images', 'public');
        
        // Créer le profil
        return Profile::create($data);
    }

    /**
     * Mettre a jour un profil
     * @param Profile $profile
     * @param array $data
     * @return Profile
     */
    public function updateProfile(Profile $profile, array $data): Profile
    {
        if (isset($data['image'])) {
            // Supprimer l'ancienne image si elle existe
            Storage::disk('public')->delete($profile->image);
            // Enregistrer la nouvelle image
            $data['image'] = $data['image']->store('images', 'public');
        }

        // Mettre à jour le profil
        $profile->update($data);

        return $profile;
    }

    /**
     * Supprimer un profil
     * @param Profile $profile
     * @return bool
     */
    public function deleteProfile(Profile $profile): bool
    {
        // Supprimer l'image liee au profil
        Storage::disk('public')->delete($profile->image);
        
        // Supprimer le profil
        return $profile->delete();
    }
}
