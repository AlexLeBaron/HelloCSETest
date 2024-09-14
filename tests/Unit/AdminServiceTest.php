<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use App\Services\AdminService;

class AdminServiceTest extends TestCase
{
    use RefreshDataBase;

    protected AdminService $adminService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminService = new AdminService();
    }

    /**
     * @test
     */
    public function registers_an_admin(): void
    {
        $data = [
            'email' => 'jean@test.com',
            'password' => 'password123',
        ];

        $admin = $this->adminService->registerAdmin($data);

        $this->assertInstanceOf(Admin::class, $admin);
        $this->assertDatabaseHas('admins', ['email' => 'jean@test.com']);
    }

    /**
     * @test
     */
    public function authenticates_an_admin_with_valid_credentials(): void
    {
        $admin = Admin::factory()->create();

        $token = $this->adminService->authenticateAdmin($admin->email, 'password123');

        $this->assertNotNull($token);
    }

    /**
     * @test
     */
    public function fails_to_authenticate_an_admin_with_wrong_credentials(): void
    {
        $admin = Admin::factory()->create();

        $token = $this->adminService->authenticateAdmin($admin->email, 'wrongpassword');

        $this->assertNull($token);
    }
}
