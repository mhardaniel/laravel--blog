<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $attributes = $request->validated();

        $attributes['password'] = Hash::make($attributes['password']);

        $user = User::create($attributes);

        auth()->login($user);

        return $this->userResponse();

    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {

            return $this->userResponse();
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('validation.invalid'),
            'errors' => [
                'user' => [trans('auth.failed')],
            ],
        ], 422);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        auth()->guard('web')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out successfully',
        ], 200);
    }

    protected function userResponse()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $user['token'] = $user->createToken($user['email'])->plainTextToken;

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);

    }
}
