<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use App\Contracts\ProfileServiceInterface;
use Illuminate\Contracts\View\View;

class ProfileController extends Controller
{
    protected ProfileServiceInterface $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * @OA\Post(
     *     path="/profiles",
     *     summary="Create a profile",
     *     description="Creates a new profile for a user",
     *     operationId="createProfile",
     *     tags={"Profiles"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="firstname", type="string", example="Jean"),
     *                 @OA\Property(property="lastname", type="string", example="Test"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="image", type="string", format="binary", description="Profile image")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Profile created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="firstname", type="string", example="Jean"),
     *             @OA\Property(property="lastname", type="string", example="Test"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Invalid data"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(StoreProfileRequest $request): JsonResponse
    {
        $profile = $this->profileService->createProfile($request->validated());

        return response()->json($profile, 201);
    }

    /**
     * @OA\Get(
     *     path="/profiles/active",
     *     summary="Get all active profiles",
     *     description="Retrieve a list of all active profiles",
     *     operationId="getActiveProfiles",
     *     tags={"Profiles"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of active profiles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="firstname", type="string"),
     *                 @OA\Property(property="lastname", type="string"),
     *                 @OA\Property(property="status", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function indexActive(Request $request): JsonResponse
    {
        $profiles = $this->profileService->getActiveProfiles();

        return response()->json($profiles, 200);
    }

    /**
     * @OA\Put(
     *     path="/profiles/{id}",
     *     summary="Update a profile",
     *     description="Updates a profile by ID",
     *     operationId="updateProfile",
     *     tags={"Profiles"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Profile ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="firstname", type="string", example="Jean"),
     *                 @OA\Property(property="lastname", type="string", example="Test"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="image", type="string", format="binary", description="Profile image")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="firstname", type="string", example="Jean"),
     *             @OA\Property(property="lastname", type="string", example="Test"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=422, description="Invalid data"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function update(UpdateProfileRequest $request,int $id): JsonResponse
    {
        $profile = Profile::findOrFail($id);
        $updatedProfile = $this->profileService->updateProfile($profile, $request->validated());

        return response()->json($updatedProfile, 200);
    }

    /**
     * @OA\Delete(
     *     path="/profiles/{id}",
     *     summary="Delete a profile",
     *     description="Deletes a profile by ID",
     *     operationId="deleteProfile",
     *     tags={"Profiles"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Profile ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Profile deleted sucessfully"),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $profile = Profile::findOrFail($id);
        $this->profileService->deleteProfile($profile);
        
        return response()->json(['message' => 'Profile deleted successfully']);
    }

    public function showProfiles(): View
    {
        $profiles = $this->profileService->getActiveProfiles();
        
        return view('profiles.index', compact('profiles'));
    }

    public function showProfile(int $id): View
    {
        $profile = $this->profileService->getProfileById($id);

        return view('profiles.show', compact('profile'));
    }
}
