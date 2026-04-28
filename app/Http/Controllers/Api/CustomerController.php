<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CustomerController extends Controller
{
    #[OA\Get(
        path: "/customers",
        summary: "List all customers",
        security: [["sanctum" => []]],
        tags: ["Customers"],
        responses: [
            new OA\Response(response: "200", description: "Success")
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $customers = Customer::orderBy('name')->get();
        return response()->json(['data' => $customers]);
    }

    #[OA\Post(
        path: "/customers",
        summary: "Create a new customer",
        security: [["sanctum" => []]],
        tags: ["Customers"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "phone", "address"],
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "phone", type: "string"),
                    new OA\Property(property: "address", type: "string"),
                    new OA\Property(property: "latitude", type: "number", format: "float"),
                    new OA\Property(property: "longitude", type: "number", format: "float")
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
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $customer = Customer::create($validated);
        return response()->json(['message' => 'Customer created', 'data' => $customer], 201);
    }

    #[OA\Get(
        path: "/customers/{id}",
        summary: "Get customer details",
        security: [["sanctum" => []]],
        tags: ["Customers"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: "200", description: "Success")
        ]
    )]
    public function show(Request $request, Customer $customer): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => $customer]);
    }

    #[OA\Put(
        path: "/customers/{id}",
        summary: "Update customer",
        security: [["sanctum" => []]],
        tags: ["Customers"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "phone", "address"],
                properties: [
                    new OA\Property(property: "name", type: "string"),
                    new OA\Property(property: "phone", type: "string"),
                    new OA\Property(property: "address", type: "string"),
                    new OA\Property(property: "latitude", type: "number", format: "float"),
                    new OA\Property(property: "longitude", type: "number", format: "float")
                ]
            )
        ),
        responses: [
            new OA\Response(response: "200", description: "Updated")
        ]
    )]
    public function update(Request $request, Customer $customer): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $customer->update($validated);
        return response()->json(['message' => 'Customer updated', 'data' => $customer]);
    }

    #[OA\Delete(
        path: "/customers/{id}",
        summary: "Delete customer",
        security: [["sanctum" => []]],
        tags: ["Customers"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: "200", description: "Deleted")
        ]
    )]
    public function destroy(Request $request, Customer $customer): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $customer->delete();
        return response()->json(['message' => 'Customer deleted']);
    }
}
