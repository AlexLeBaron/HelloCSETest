<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
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

// Authentication Routes
Route::post('/admin/register',[AdminAuthController::class, 'register']); // Register an administrator
Route::post('/admin/login',[AdminAuthController::class, 'login']); // Authenticate an administrator

// Profiles Routes
Route::middleware('auth:sanctum')->group(function() {
    Route::post('/profiles', [ProfileController::class, 'store']);  // Create a profile
    Route::put('/profiles/{id}', [ProfileController::class, 'update']);  // Update an existing profile
    Route::delete('/profiles/{id}', [ProfileController::class, 'destroy']);  // Delete a profile
});
Route::get('/profiles/active', [ProfileController::class, 'indexActive']); // Get active profiles
