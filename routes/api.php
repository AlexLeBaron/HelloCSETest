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

// Routes d'authentication
Route::post('/admin/register',[AdminAuthController::class, 'register']); // Enregister un administrateur
Route::post('/admin/login',[AdminAuthController::class, 'login']); // Authentifier un administrateur

// Routes de gestion de profil
Route::middleware('auth:sanctum')->group(function() {
    Route::post('/profiles', [ProfileController::class, 'store']);  // Créer un profil
    Route::put('/profiles/{id}', [ProfileController::class, 'update']);  // Modifier un profil
    Route::delete('/profiles/{id}', [ProfileController::class, 'destroy']);  // Supprimer un profil
});

// Routes d'acces aux donnees publiques
Route::get('/profiles/active', [ProfileController::class, 'indexActive']); // Acceder aux profils actifs
