<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => Role::all()]);
    }

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

    public function show(Request $request, Role $role): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => $role]);
    }

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

    public function destroy(Request $request, Role $role): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }
}
