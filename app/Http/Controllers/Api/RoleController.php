<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RoleController extends Controller
{
    #[OA\Get(
        path: "/roles",
        summary: "List all roles",
        security: [["sanctum" => []]],
        tags: ["Roles"],
        responses: [
            new OA\Response(response: "200", description: "Success")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => Role::all()]);
    }

    #[OA\Post(
        path: "/roles",
        summary: "Create a new role",
        security: [["sanctum" => []]],
        tags: ["Roles"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["code", "name"],
                properties: [
                    new OA\Property(property: "code", type: "string"),
                    new OA\Property(property: "name", type: "string")
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
            'code' => 'required|string|unique:roles,code|max:20',
            'name' => 'required|string|max:255',
        ]);

        $role = Role::create($validated);
        return response()->json(['message' => 'Role created', 'data' => $role], 201);
    }

    #[OA\Get(
        path: "/roles/{id}",
        summary: "Get role details",
        security: [["sanctum" => []]],
        tags: ["Roles"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: "200", description: "Success")
        ]
    )]
    public function show(Request $request, Role $role): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => $role]);
    }

    #[OA\Put(
        path: "/roles/{id}",
        summary: "Update role",
        security: [["sanctum" => []]],
        tags: ["Roles"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["code", "name"],
                properties: [
                    new OA\Property(property: "code", type: "string"),
                    new OA\Property(property: "name", type: "string")
                ]
            )
        ),
        responses: [
            new OA\Response(response: "200", description: "Updated")
        ]
    )]
    public function update(Request $request, Role $role): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:roles,code,'.$role->id,
            'name' => 'required|string|max:255',
        ]);

        $role->update($validated);
        return response()->json(['message' => 'Role updated', 'data' => $role]);
    }

    #[OA\Delete(
        path: "/roles/{id}",
        summary: "Delete role",
        security: [["sanctum" => []]],
        tags: ["Roles"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: "200", description: "Deleted")
        ]
    )]
    public function destroy(Request $request, Role $role): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }
}
