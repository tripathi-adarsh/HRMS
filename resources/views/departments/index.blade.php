@extends('layouts.app')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div><h4>Departments</h4><p>Manage company departments</p></div>
    <a href="{{ route('departments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Department</a>
</div>
<div class="card">
    <div class="card-header">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search departments..." value="{{ request('search') }}" style="max-width:300px">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>#</th><th>Name</th><th>Description</th><th>Employees</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($departments as $dept)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $dept->name }}</td>
                    <td>{{ $dept->description ?? '-' }}</td>
                    <td><span class="badge bg-primary">{{ $dept->employees_count }}</span></td>
                    <td><span class="badge {{ $dept->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $dept->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('departments.edit', $dept) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('departments.destroy', $dept) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No departments found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($departments->hasPages())
    <div class="card-footer">{{ $departments->links() }}</div>
    @endif
</div>
@endsection