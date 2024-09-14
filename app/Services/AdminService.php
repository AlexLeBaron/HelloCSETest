<?php

namespace App\Services;

use App\Contracts\AdminServiceInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminService implements AdminServiceInterface
{
    /**
     * Enregistrer un admin
     * @param array $data
     * @return Admin
     */
    public function registerAdmin(array $data): Admin
    {
        $data['password'] = Hash::make($data['password']);
        return Admin::create($data);
    }

    /**
     * Authentifier un administrateur.
     * @param string $email
     * @param string $password
     * @return string|null
     */
    public function authenticateAdmin(string $email, string $password): ?string
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $admin = Auth::user();
            return $admin->createToken('admin-token')->plainTextToken;
        }

        return null;
    }
}
