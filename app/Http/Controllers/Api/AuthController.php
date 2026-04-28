<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = $request->user()->load('role');
        $token = $user->createToken('auth_token')->plainTextToken;
        
        $redirectUrl = $user->isAdmin() ? '/admin/dashboard' : '/technician/dashboard';

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'role' => $user->role->code,
                'is_admin' => $user->isAdmin(),
                'token' => $token,
                'redirect' => $redirectUrl,
            ],
        ]);
    }

    /**
     * Revoke the user's current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
