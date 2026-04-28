<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $users = User::with(['role', 'supervisor'])->get();
        return response()->json(['data' => $users]);
    }

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

    public function show(Request $request, User $user): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => $user->load(['role', 'supervisor'])]);
    }

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

    public function destroy(Request $request, User $user): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
