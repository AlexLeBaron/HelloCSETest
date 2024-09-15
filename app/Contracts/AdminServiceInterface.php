<?php

namespace App\Contracts;

use App\Models\Admin;

interface AdminServiceInterface
{
    /**
     * Register an admin
     * @param array $data
     * @return Admin
     */
    public function registerAdmin(array $data): Admin;

    /**
     * Authenticate an admin
     * @param string $email
     * @param string $password
     * @return string|null
     */
    public function authenticateAdmin(string $email, string $password): ?string;
}
