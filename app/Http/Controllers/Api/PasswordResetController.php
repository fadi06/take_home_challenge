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
    // Send password reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => __('global.user_not_found')], 404);
        }

        // Create a password reset token
        $token = Password::createToken($user);

        return $this->sendSuccessResponse(message: __('global.password_reset_link_send'), result: ['token' => $token, 'email' => $user->email]);
    }

    // Reset password
    public function reset(PasswordResetFormRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendErrorResponse(message: __('global.user_not_found'));
        }

        // Verify the token
        $isValid = Password::tokenExists($user, $request->token);

        if (!$isValid) {
            return $this->sendErrorResponse(message: __('global.invalid_token'));
        }

        // Reset the password
        $user->password = Hash::make($request->password);

        $user->save();

        DB::table('password_reset_tokens')->whereEmail($request->email)->delete();

        return $this->sendSuccessResponse(__('global.password_reset_success'), $user);
    }
}
