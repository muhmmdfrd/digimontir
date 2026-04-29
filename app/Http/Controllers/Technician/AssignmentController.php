<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function show(Request $request, $id)
    {
        $assignment = Assignment::with(['admin', 'technician', 'customer', 'status'])->findOrFail($id);
        
        if ($assignment->technician_id !== $request->user()->id) {
            abort(403);
        }

        return view('technician.assignments.show', compact('assignment'));
    }

    public function checkIn(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        if ($assignment->technician_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'lat_check_in' => 'required|numeric',
            'lng_check_in' => 'required|numeric',
            'check_in_photo' => 'required|image|max:5120',
        ]);

        $path = $request->file('check_in_photo')->store('assignments/check_in', 'public');

        // Status update to "In Progress" - assuming Status ID 2 is In Progress or we just leave status as is.
        // Let's just update the Assignment data for now.
        $status_ckin = \App\Models\Status::where('code', 'CKIN')->first();

        $assignment->update([
            'lat_check_in' => $request->lat_check_in,
            'lng_check_in' => $request->lng_check_in,
            'check_in_photo_path' => $path,
            'status_id' => $status_ckin->id ?? $assignment->status_id,
        ]);

        return redirect()->route('technician.dashboard')->with('success', 'Checked in successfully.');
    }

    public function checkOut(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        if ($assignment->technician_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'lat_check_out' => 'required|numeric',
            'lng_check_out' => 'required|numeric',
            'check_out_photo' => 'required|image|max:5120',
            'description_by_technician' => 'required|string',
        ]);

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

        return redirect()->route('technician.dashboard')->with('success', 'Checked out successfully.');
    }
}
