<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use Response;

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Registers a new user and returns user details along with an authentication token.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Registration request payload",
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Fawad"),
     *             @OA\Property(property="email", type="string", format="email", example="fawad@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Fawad"),
     *                 @OA\Property(property="email", type="string", format="email", example="fawad@example.com"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-05T16:41:33.000000Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-05T16:41:33.000000Z"),
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="token", type="string", example="7|87eHdMh8jIC6XSYpKy4Nab22A4zf89l90Mz1xayveab76f3a")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request - validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="email", type="string", example="The email field is required."),
     *             )
     *         )
     *     )
     * )
     */
    public function register(RegisterFormRequest $request)
    {
        $user = User::create($request->validated());

        $user->token = $user->createToken('api-token')->plainTextToken;

        return $this->sendSuccessResponse(message: __('global.user_register_successfully'), result: $user);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     description="Logs in a user with email and password and returns an auth token.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login request payload",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="fawad@example1.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged in successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=6),
     *                 @OA\Property(property="name", type="string", example="Fawad"),
     *                 @OA\Property(property="email", type="string", format="email", example="fawad@example1.com"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-10-29T14:54:14.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-10-29T14:54:14.000000Z"),
     *                 @OA\Property(property="token", type="string", example="8|Al29wK19v2cKu8vDPyGOwk7olBxYOUb7fcuaqXBP27755e73")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid email or password",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Invalid email or password")
     *         )
     *     )
     * )
     */

    public function login(LoginFormRequest $request)
    {
        $user = User::where('email', $request->validated()['email'])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendErrorResponse(message: __('global.invalid_credentials'), code: 401);
        }

        $user->token = $user->createToken('api-token')->plainTextToken;

        return $this->sendSuccessResponse(message: __('global.user_logged_in_successfully'), result: $user);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="User logout",
     *     description="Logs out the authenticated user.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged out successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Auth token missing or invalid",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendSuccessResponse(message: __('global.user_logged_out_successfully'));
    }
}