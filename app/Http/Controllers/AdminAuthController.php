<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Contracts\AdminServiceInterface;
use App\Http\Requests\RegisterAdminRequest;
use App\Http\Requests\LoginAdminRequest;
use Illuminate\Auth\AuthenticationException;

class AdminAuthController extends Controller
{
    protected AdminServiceInterface $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * @OA\Post(
     *     path="/admin/register",
     *     summary="Register a new admin",
     *     description="Creates a new admin and returns their details",
     *     operationId="registerAdmin",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="jean@test.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Admin created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="email", type="string", example="jean@test.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function register(RegisterAdminRequest $request): JsonResponse
    {
        $admin = $this->adminService->registerAdmin($request->validated());

        return response()->json($admin, 201);
    }

    /**
     * @OA\Post(
     *      path="/admin/login",
     *      summary="Admin login",
     *      description="Authenticates an admin and returns a token",
     *      operationId="loginAdmin",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="email",type="string",example="jean@test.com"),
     *              @OA\Property(property="password",type="string",example="password123"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Token returned",
     *          @OA\JsonContent(
     *              @OA\Property(property="token",type="string",example="Bearer token")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Invalid credentials"
     *      )
     * )
     */
    public function login(LoginAdminRequest $request): JsonResponse
    {
        $token = $this->adminService->authenticateAdmin($request->email, $request->password);

        if ($token) return response()->json(['token' => $token], 200);
        throw new AuthenticationException('Unauthorized');
    }
}
