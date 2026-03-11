<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatusRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StatusController extends Controller
{
    public function index(): View
    {
        $statuses = Status::query()->orderBy('code')->paginate(15);

        return view('admin.statuses.index', compact('statuses'));
    }

    public function create(): View
    {
        return view('admin.statuses.create');
    }

    public function store(StoreStatusRequest $request): RedirectResponse
    {
        Status::query()->create($request->validated());

        return redirect()->route('admin.statuses.index')->with('success', 'Status created successfully.');
    }

    public function show(Status $status): View
    {
        return view('admin.statuses.show', compact('status'));
    }

    public function edit(Status $status): View
    {
        return view('admin.statuses.edit', compact('status'));
    }

    public function update(UpdateStatusRequest $request, Status $status): RedirectResponse
    {
        $status->update($request->validated());

        return redirect()->route('admin.statuses.index')->with('success', 'Status updated successfully.');
    }

    public function destroy(Status $status): RedirectResponse
    {
        $status->delete();

        return redirect()->route('admin.statuses.index')->with('success', 'Status deleted successfully.');
    }
}
