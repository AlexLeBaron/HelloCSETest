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
     * Register an admin
     * @param array $data
     * @return Admin
     */
    public function registerAdmin(array $data): Admin
    {
        $data['password'] = Hash::make($data['password']);
        return Admin::create($data);
    }

    /**
     * Authenticate an admin
     * @param string $email
     * @param string $password
     * @return string|null
     */
    public function authenticateAdmin(string $email, string $password): ?string
    {
        // Get admin using mail
        $admin = Admin::where('email', $email)->first();

        // Check if admin exists and passwords match
        if ($admin && Hash::check($password, $admin->password)) {
            return $admin->createToken('admin-token')->plainTextToken;
        }

        return null;
    }
}
