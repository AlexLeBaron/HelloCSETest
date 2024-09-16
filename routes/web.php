<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route to get all active profiles
Route::get('/profiles', [ProfileController::class, 'showProfiles']);

// Route protected for profiles managment (administrator only)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profiles/{id}', [ProfileController::class, 'showProfile']);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});
