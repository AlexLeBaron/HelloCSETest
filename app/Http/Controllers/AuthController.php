<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\RegisterAdminRequest;
use App\Http\Requests\LoginAdminRequest;
use App\Contracts\AdminServiceInterface;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected AdminServiceInterface $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Register new admin
    public function register(RegisterAdminRequest $request): RedirectResponse
    {
        $admin = $this->adminService->registerAdmin($request->validated());

        return redirect()->route('login')->with('success', 'Inscription réussie, vous pouvez vous connecter.');
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login admin
    public function login(LoginAdminRequest $request): RedirectResponse
    {
        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            if ($request->hasSession()) {
                $request->session()->regenerate();
            }

            return redirect()->route('admin.dashboard')->with('success', 'You are logged in');
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification sont incorrectes.',
        ]);
    }

    // Logout admin
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard()
    {
        $profiles = Profile::all();

        return view('admin.dashboard', compact('profiles'));
    }
}
