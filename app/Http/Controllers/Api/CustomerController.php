<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $customers = Customer::orderBy('name')->get();
        return response()->json(['data' => $customers]);
    }

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

    public function show(Request $request, Customer $customer): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        return response()->json(['data' => $customer]);
    }

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

    public function destroy(Request $request, Customer $customer): JsonResponse
    {
        if (!$request->user()->isAdmin()) abort(403);
        $customer->delete();
        return response()->json(['message' => 'Customer deleted']);
    }
}
