<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Assignment::with(['admin', 'technician', 'customer', 'status']);
        
        if (!$user->isAdmin()) {
            $query->where('technician_id', $user->id);
        }

        if ($request->has('tab')) {
            switch ($request->tab) {
                case 'past':
                    $query->whereDate('scheduled_date', '<', now()->toDateString());
                    break;
                case 'today':
                    $query->whereDate('scheduled_date', '=', now()->toDateString());
                    break;
                case 'upcoming':
                    $query->whereDate('scheduled_date', '>', now()->toDateString());
                    break;
            }
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }
        
        $assignments = $query->orderBy('scheduled_date', 'asc')->latest()->get();
        
        return AssignmentResource::collection($assignments);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = $request->user();
        $assignment = Assignment::with(['admin', 'technician', 'customer', 'status'])->findOrFail($id);
        
        if (!$user->isAdmin() && $assignment->technician_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
        
        return new AssignmentResource($assignment);
    }

    /**
     * Check-in action for technician.
     */
    public function checkIn(Request $request, string $id)
    {
        $user = $request->user();
        $assignment = Assignment::findOrFail($id);
        
        if (!$user->isAdmin() && $assignment->technician_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'lat_check_in' => 'required|numeric',
            'lng_check_in' => 'required|numeric',
            'check_in_photo' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->file('check_in_photo')->store('assignments/check_in', 'public');

        $status_ckin = \App\Models\Status::where('code', 'CKIN')->first();

        $assignment->update([
            'lat_check_in' => $request->lat_check_in,
            'lng_check_in' => $request->lng_check_in,
            'check_in_photo_path' => $path,
            'status_id' => $status_ckin->id ?? $assignment->status_id,
        ]);

        return new AssignmentResource($assignment);
    }

    /**
     * Check-out action for technician.
     */
    public function checkOut(Request $request, string $id)
    {
        $user = $request->user();
        $assignment = Assignment::findOrFail($id);
        
        if (!$user->isAdmin() && $assignment->technician_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'lat_check_out' => 'required|numeric',
            'lng_check_out' => 'required|numeric',
            'check_out_photo' => 'required|image|max:5120',
            'description_by_technician' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->file('check_out_photo')->store('assignments/check_out', 'public');

        $status_wrev = \App\Models\Status::where('code', 'WREV')->first();

        $assignment->update([
            'lat_check_out' => $request->lat_check_out,
            'lng_check_out' => $request->lng_check_out,
            'check_out_photo_path' => $path,
            'description_by_technician' => $request->description_by_technician,
            'completed_at' => now(),
            'status_id' => $status_wrev->id ?? $assignment->status_id,
        ]);

        return new AssignmentResource($assignment);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'status_id' => 'required|exists:statuses,id',
            'scheduled_date' => 'required|date',
            'description_by_admin' => 'required|string',
        ]);

        $validated['admin_id'] = $request->user()->id;

        $assignment = Assignment::create($validated);

        return new AssignmentResource($assignment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $assignment = Assignment::findOrFail($id);

        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'status_id' => 'required|exists:statuses,id',
            'scheduled_date' => 'required|date',
            'description_by_admin' => 'required|string',
        ]);

        $assignment->update($validated);

        return new AssignmentResource($assignment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if (!$request->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $assignment = Assignment::findOrFail($id);
        $assignment->delete();

        return response()->json(['message' => 'Assignment deleted']);
    }

    /**
     * Admin review and closing action.
     */
    public function review(Request $request, string $id)
    {
        $user = $request->user();
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $assignment = Assignment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review_by_admin' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $status_cmpt = \App\Models\Status::where('code', 'CMPT')->first();

        $assignment->update([
            'rating' => $request->rating,
            'review_by_admin' => $request->review_by_admin,
            'closed_at' => now(),
            'status_id' => $status_cmpt->id ?? $assignment->status_id,
        ]);

        return new AssignmentResource($assignment);
    }
}
