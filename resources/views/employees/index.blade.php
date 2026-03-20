@extends('layouts.app')
@section('title', 'Employees')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Employees</h4>
        <p>Manage all employees in your organization</p>
    </div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Employee
    </a>
</div>

<!-- Stats row -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-primary">{{ $employees->total() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Total Employees</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-success">{{ $employees->where('status','active')->count() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Active</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-secondary">{{ $employees->where('status','inactive')->count() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Inactive</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-warning">{{ $departments->count() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Departments</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1" style="font-size:0.78rem">Search</label>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.85rem"></i>
                    <input type="text" name="search" class="form-control ps-4" placeholder="Name, email or ID..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:0.78rem">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>ID</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Phone</th>
                        <th>Salary</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($emp->photo)
                                    <img src="{{ asset('storage/'.$emp->photo) }}" class="rounded-circle" width="36" height="36" style="object-fit:cover">
                                @else
                                    <div class="avatar" style="width:36px;height:36px;font-size:0.85rem;background:{{ ['#4f46e5','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4'][crc32($emp->name) % 6] }}">
                                        {{ substr($emp->name,0,1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-semibold" style="font-size:0.875rem">{{ $emp->name }}</div>
                                    <small class="text-muted">{{ $emp->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td><code class="bg-light px-2 py-1 rounded" style="font-size:0.78rem">{{ $emp->employee_id }}</code></td>
                        <td>
                            @if($emp->department)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $emp->department->name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td style="font-size:0.85rem">{{ $emp->designation->name ?? '—' }}</td>
                        <td style="font-size:0.85rem">{{ $emp->phone ?? '—' }}</td>
                        <td class="fw-semibold" style="font-size:0.875rem">&#8377;{{ number_format($emp->salary) }}</td>
                        <td style="font-size:0.82rem;color:#64748b">
                            {{ $emp->joining_date ? \Carbon\Carbon::parse($emp->joining_date)->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <span class="badge {{ $emp->status === 'active' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle' }}">
                                <i class="bi bi-circle-fill me-1" style="font-size:0.45rem"></i>{{ ucfirst($emp->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('employees.show', $emp) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('employees.edit', $emp) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('employees.destroy', $emp) }}" class="d-inline" onsubmit="return confirm('Delete {{ $emp->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-people d-block mb-2" style="font-size:2.5rem;opacity:0.25"></i>
                            No employees found. <a href="{{ route('employees.create') }}">Add one now</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($employees->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $employees->firstItem() }}–{{ $employees->lastItem() }} of {{ $employees->total() }} employees</small>
        {{ $employees->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
