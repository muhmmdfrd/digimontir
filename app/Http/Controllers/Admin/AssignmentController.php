<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Customer;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Assignment::with(['admin', 'technician', 'customer', 'status']);

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

        if ($request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        $assignments = $query->orderBy('scheduled_date', 'asc')->latest()->paginate(15)->withQueryString();
        
        $technicians = User::whereHas('role', fn($q) => $q->where('code', 'TECH'))->get();
        $statuses = Status::orderBy('id')->get();
            
        return view('admin.assignments.index', compact('assignments', 'technicians', 'statuses'));
    }

    public function create()
    {
        $technicians = User::whereHas('role', fn($q) => $q->where('code', 'TECH'))->get();
        $customers = Customer::orderBy('name')->get();
        $statuses = Status::orderBy('id')->get();
        
        return view('admin.assignments.create', compact('technicians', 'customers', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'status_id' => 'required|exists:statuses,id',
            'scheduled_date' => 'required|date',
            'description_by_admin' => 'required|string',
        ]);

        $validated['admin_id'] = $request->user()->id;

        Assignment::create($validated);

        return redirect()->route('admin.assignments.index')->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['admin', 'technician', 'customer', 'status']);
        return view('admin.assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment)
    {
        $technicians = User::whereHas('role', fn($q) => $q->where('code', 'TECH'))->get();
        $customers = Customer::orderBy('name')->get();
        $statuses = Status::orderBy('id')->get();
        
        return view('admin.assignments.edit', compact('assignment', 'technicians', 'customers', 'statuses'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'status_id' => 'required|exists:statuses,id',
            'scheduled_date' => 'required|date',
            'description_by_admin' => 'required|string',
        ]);

        $assignment->update($validated);

        return redirect()->route('admin.assignments.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('admin.assignments.index')->with('success', 'Assignment deleted successfully.');
    }

    public function complete(Request $request, Assignment $assignment)
    {
        $status_cmpt = \App\Models\Status::where('code', 'CMPT')->first();
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_by_admin' => 'required|string',
        ]);
        
        $assignment->update([
            'status_id' => $status_cmpt->id ?? $assignment->status_id,
            'rating' => $request->rating,
            'review_by_admin' => $request->review_by_admin,
            'closed_at' => now(),
        ]);
        
        return redirect()->route('admin.assignments.show', $assignment)->with('success', 'Assignment marked as completed.');
    }

    public function returnTask(Request $request, Assignment $assignment)
    {
        $status_rtrn = \App\Models\Status::where('code', 'RTRN')->first();
        $validated = $request->validate([
            'review_by_admin' => 'required|string',
        ]);
        
        $assignment->update([
            'status_id' => $status_rtrn->id ?? $assignment->status_id,
            'review_by_admin' => $request->review_by_admin,
            'completed_at' => null, 
        ]);
        
        return redirect()->route('admin.assignments.show', $assignment)->with('success', 'Assignment returned to technician for corrections.');
    }
}
