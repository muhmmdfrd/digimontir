<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => Status::all()]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        
        $validated = $request->validate([
            'code' => 'required|string|unique:statuses,code|max:20',
            'name' => 'required|string|max:255',
        ]);

        $status = Status::create($validated);
        return response()->json(['message' => 'Status created', 'data' => $status], 201);
    }

    public function show(Request $request, Status $status): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => $status]);
    }

    public function update(Request $request, Status $status): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:statuses,code,'.$status->id,
            'name' => 'required|string|max:255',
        ]);

        $status->update($validated);
        return response()->json(['message' => 'Status updated', 'data' => $status]);
    }

    public function destroy(Request $request, Status $status): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $status->delete();
        return response()->json(['message' => 'Status deleted']);
    }
}
