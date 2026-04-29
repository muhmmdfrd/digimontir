<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class StatusController extends Controller
{
    #[OA\Get(
        path: "/statuses",
        summary: "List all statuses",
        security: [["sanctum" => []]],
        tags: ["Statuses"],
        responses: [
            new OA\Response(response: "200", description: "Success")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => Status::all()]);
    }

    #[OA\Post(
        path: "/statuses",
        summary: "Create a new status",
        security: [["sanctum" => []]],
        tags: ["Statuses"],
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
            'code' => 'required|string|unique:statuses,code|max:20',
            'name' => 'required|string|max:255',
        ]);

        $status = Status::create($validated);
        return response()->json(['message' => 'Status created', 'data' => $status], 201);
    }

    #[OA\Get(
        path: "/statuses/{id}",
        summary: "Get status details",
        security: [["sanctum" => []]],
        tags: ["Statuses"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: "200", description: "Success")
        ]
    )]
    public function show(Request $request, Status $status): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => $status]);
    }

    #[OA\Put(
        path: "/statuses/{id}",
        summary: "Update status",
        security: [["sanctum" => []]],
        tags: ["Statuses"],
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

    #[OA\Delete(
        path: "/statuses/{id}",
        summary: "Delete status",
        security: [["sanctum" => []]],
        tags: ["Statuses"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: "200", description: "Deleted")
        ]
    )]
    public function destroy(Request $request, Status $status): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $status->delete();
        return response()->json(['message' => 'Status deleted']);
    }
}
