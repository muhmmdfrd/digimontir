<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->assignments()->with(['customer', 'status']);

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

        $assignments = $query->orderBy('scheduled_date', 'asc')->paginate(15)->withQueryString();
        $statuses = \App\Models\Status::orderBy('id')->get();

        return view('technician.dashboard', compact('assignments', 'statuses'));
    }
}
