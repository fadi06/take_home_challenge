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

    // User Registration
    public function register(RegisterFormRequest $request)
    {
        $user = User::create($request->validated());

        $user->toekn = $user->createToken('api-token')->plainTextToken;

        return $this->sendSuccessResponse(message: __('global.user_register_successfully'), result: $user);
    }

    // User Login
    public function login(LoginFormRequest $request)
    {
        $user = User::where('email', $request->validated()['email'])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendErrorResponse(message: __('global.invalid_credentials'), code: 401);
        }

        $user->toekn = $user->createToken('api-token')->plainTextToken;

        return $this->sendSuccessResponse(message: __('global.user_logged_in_successfully'), result: $user);

    }

    // User Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendSuccessResponse(message: __('global.user_logged_out_successfully'));
    }
}
