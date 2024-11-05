<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetFormRequest;
use App\Models\User;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    use Response;

    /**
     * @OA\Post(
     *     path="/api/password/email",
     *     tags={"Auth"},
     *     summary="Send password reset link",
     *     description="Sends a password reset link to the user’s email address.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset link sent successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password reset link sent.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     )
     * )
     */

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => __('global.user_not_found')], 404);
        }

        $token = Password::createToken($user);

        return $this->sendSuccessResponse(message: __('global.password_reset_link_send'), result: ['token' => $token, 'email' => $user->email]);
    }

    /**
     * @OA\Post(
     *     path="/api/password/reset",
     *     tags={"Auth"},
     *     summary="Reset password",
     *     description="Resets the user's password using the token received via email.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "token", "password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="token", type="string", example="reset-token"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password reset successful."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     )
     * )
     */

    public function reset(PasswordResetFormRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendErrorResponse(message: __('global.user_not_found'));
        }

        $isValid = Password::tokenExists($user, $request->token);

        if (!$isValid) {
            return $this->sendErrorResponse(message: __('global.invalid_token'));
        }

        $user->password = Hash::make($request->password);

        $user->save();

        DB::table('password_reset_tokens')->whereEmail($request->email)->delete();

        return $this->sendSuccessResponse(__('global.password_reset_success'), $user);
    }
}