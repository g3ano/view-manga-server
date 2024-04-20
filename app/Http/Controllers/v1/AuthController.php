<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Auth\LoginRequest;
use App\Http\Requests\v1\Auth\RegisterRequest;
use App\Http\Resources\v1\UserResource;
use App\Models\v1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            $this->failure([
                'message' => 'The provided credentials are incorrect'
            ], 422);
        }

        /** @var User $user */
        $user = auth()->user();
        $user->tokens()->delete();

        $token = $user->createToken(
            'userToken',
            ['*'],
            Carbon::now()->addDays(10)
        )->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->success([
            'message' => 'Logged out',
        ]);
    }


    public function register(RegisterRequest $request)
    {
        $credentials = $request->validated();

        /** @var User $user */
        $user = User::create([
            'username' => $credentials['username'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
        ]);

        $token = $user->createToken(
            'userToken',
            ['*'],
            Carbon::now()->addDays(15)
        )->plainTextToken;

        return $this->success([
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }
}
