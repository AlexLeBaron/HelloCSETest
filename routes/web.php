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

Route::middleware('web')->group(function () {
    
    // Public Route to get all active profiles
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/profiles', [ProfileController::class, 'showProfiles'])->name('profiles.index');

    // Routes for authentication (register, login, logout)
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected routes for authenticated administrators only
    Route::middleware('auth:web')->group(function () {
        Route::get('/profiles/{id}', [ProfileController::class, 'showProfile'])->name('profiles.show');
        Route::get('/admin/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
    });

});
