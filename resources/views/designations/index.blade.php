@extends('layouts.app')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div><h4>Designations</h4><p>Manage job designations</p></div>
    <a href="{{ route('designations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Designation</a>
</div>
<div class="card">
    <div class="card-header">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}" style="max-width:250px">
            <select name="department_id" class="form-select" style="max-width:200px">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>#</th><th>Designation</th><th>Department</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($designations as $desig)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $desig->name }}</td>
                    <td>{{ $desig->department->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('designations.edit', $desig) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('designations.destroy', $desig) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">No designations found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($designations->hasPages())
    <div class="card-footer">{{ $designations->links() }}</div>
    @endif
</div>
@endsection