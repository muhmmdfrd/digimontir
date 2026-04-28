<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: "/login",
        summary: "Authenticate user and grant token",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "admin@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password")
                ]
            )
        ),
        responses: [
            new OA\Response(response: "200", description: "Login successful"),
            new OA\Response(response: "401", description: "Invalid credentials")
        ]
    )]
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

    #[OA\Post(
        path: "/logout",
        summary: "Revoke the user's token",
        security: [["sanctum" => []]],
        tags: ["Authentication"],
        responses: [
            new OA\Response(response: "200", description: "Successfully logged out")
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
