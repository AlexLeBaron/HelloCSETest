<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;

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
Route::get('/profiles', [ProfileController::class, 'showProfiles'])->name('profiles.index');

// Route protected for profiles managment (administrator only)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profiles/{id}', [ProfileController::class, 'showProfile'])->name('profiles.show');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// Route to register and login
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Route to logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

