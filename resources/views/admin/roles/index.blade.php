@extends('admin.layout')

@section('title', 'Roles')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Roles</h1>
        <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">Add Role</a>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 dark:bg-slate-700/50">
                <tr>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Code</th>
                    <th class="text-left px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Name</th>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-600 dark:text-slate-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-4 py-3">{{ $role->code }}</td>
                        <td class="px-4 py-3">{{ $role->name }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.roles.show', $role) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm">View</a>
                            <a href="{{ route('admin.roles.edit', $role) }}" class="ml-3 text-indigo-600 dark:text-indigo-400 hover:underline text-sm">Edit</a>
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline ml-3" onsubmit="return confirm('Delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No roles yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($roles->hasPages())
            <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
@endsection
