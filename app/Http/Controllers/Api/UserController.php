<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: "/users",
        summary: "List all users",
        security: [["sanctum" => []]],
        tags: ["Users"],
        responses: [
            new OA\Response(response: "200", description: "Success")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $users = User::with(['role', 'supervisor'])->get();
        return response()->json(['data' => $users]);
    }

    #[OA\Post(
        path: "/users",
        summary: "Create a new user",
        security: [["sanctum" => []]],
        tags: ["Users"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password", "role_id"],
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "email", type: "string", format: "email"),
                    new OA\Property(property: "password", type: "string", format: "password"),
                    new OA\Property(property: "role_id", type: "integer"),
                    new OA\Property(property: "supervisor_id", type: "integer", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: "201", description: "Created")
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        
        return response()->json(['message' => 'User created', 'data' => $user->load(['role', 'supervisor'])], 201);
    }

    #[OA\Get(
        path: "/users/{id}",
        summary: "Get user details",
        security: [["sanctum" => []]],
        tags: ["Users"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: "200", description: "Success")
        ]
    )]
    public function show(Request $request, User $user): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => $user->load(['role', 'supervisor'])]);
    }

    #[OA\Put(
        path: "/users/{id}",
        summary: "Update user",
        security: [["sanctum" => []]],
        tags: ["Users"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "role_id"],
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "email", type: "string", format: "email"),
                    new OA\Property(property: "password", type: "string", format: "password", nullable: true),
                    new OA\Property(property: "role_id", type: "integer"),
                    new OA\Property(property: "supervisor_id", type: "integer", nullable: true)
                ]
            )
        ),
        responses: [
            new OA\Response(response: "200", description: "Updated")
        ]
    )]
    public function update(Request $request, User $user): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,'.$user->id,
            'password' => ['nullable', Password::defaults()],
            'role_id' => 'required|exists:roles,id',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json(['message' => 'User updated', 'data' => $user->load(['role', 'supervisor'])]);
    }

    #[OA\Delete(
        path: "/users/{id}",
        summary: "Delete user",
        security: [["sanctum" => []]],
        tags: ["Users"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: "200", description: "Deleted")
        ]
    )]
    public function destroy(Request $request, User $user): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
