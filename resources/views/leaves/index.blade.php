@extends('layouts.app')
@section('title', 'Leave Management')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Leave Management</h4>
        <p>Review and manage employee leave requests</p>
    </div>
    <a href="{{ route('leaves.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Apply Leave
    </a>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-warning">{{ $leaves->where('status','pending')->count() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-success">{{ $leaves->where('status','approved')->count() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Approved</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-danger">{{ $leaves->where('status','rejected')->count() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Rejected</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-primary">{{ $leaves->total() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Total Requests</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1" style="font-size:0.78rem">Search Employee</label>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.85rem"></i>
                    <input type="text" name="search" class="form-control ps-4" placeholder="Employee name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Leave Type</label>
                <select name="leave_type_id" class="form-select">
                    <option value="">All Types</option>
                    @foreach(\App\Models\LeaveType::all() as $lt)
                        <option value="{{ $lt->id }}" {{ request('leave_type_id') == $lt->id ? 'selected' : '' }}>{{ $lt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="width:32px;height:32px;font-size:0.8rem">
                                    {{ substr($leave->employee->name ?? 'N', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.875rem">{{ $leave->employee->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $leave->employee->department->name ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info-subtle text-info border border-info-subtle">{{ $leave->leaveType->name ?? 'N/A' }}</span>
                        </td>
                        <td style="font-size:0.85rem">{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                        <td style="font-size:0.85rem">{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $leave->days }} day{{ $leave->days > 1 ? 's' : '' }}</span>
                        </td>
                        <td style="font-size:0.82rem;max-width:180px">
                            <span title="{{ $leave->reason }}">{{ \Illuminate\Support\Str::limit($leave->reason, 35) }}</span>
                        </td>
                        <td>
                            @if($leave->status === 'approved')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="bi bi-check-circle me-1"></i>Approved
                                </span>
                            @elseif($leave->status === 'rejected')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                    <i class="bi bi-x-circle me-1"></i>Rejected
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                    <i class="bi bi-clock me-1"></i>Pending
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if(auth()->user()->role !== 'employee' && $leave->status === 'pending')
                                <form method="POST" action="{{ route('leaves.approve', $leave) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form method="POST" action="{{ route('leaves.reject', $leave) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                                </form>
                                @endif
                                @if($leave->status === 'pending')
                                <form method="POST" action="{{ route('leaves.destroy', $leave) }}" class="d-inline" onsubmit="return confirm('Delete this leave request?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-calendar-x d-block mb-2" style="font-size:2.5rem;opacity:0.25"></i>
                            No leave requests found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($leaves->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $leaves->firstItem() }}–{{ $leaves->lastItem() }} of {{ $leaves->total() }} requests</small>
        {{ $leaves->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
