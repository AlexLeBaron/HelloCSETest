<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ProfileServiceInterface;
use App\Services\ProfileService;
use App\Contracts\AdminServiceInterface;
use App\Services\AdminService;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API documentation for managing profiles and admin authentication",
 *     @OA\Contact(
 *         email="jean@test.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local server"
 * )
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->bind(AdminServiceInterface::class, AdminService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
