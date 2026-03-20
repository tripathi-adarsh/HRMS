@extends('layouts.app')
@section('title', 'Leave Report')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Leave Report</h4>
        <p>Annual leave summary with filters by employee, department, type and status</p>
    </div>
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2" style="font-size:0.82rem;border-radius:8px">
        Year {{ $year }}
    </span>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #6366f1">
            <div class="fw-bold fs-4 text-primary">{{ $leaves->count() }}</div>
            <div class="text-muted" style="font-size:0.78rem">Total Requests</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #10b981">
            <div class="fw-bold fs-4 text-success">{{ $approvedDays }}</div>
            <div class="text-muted" style="font-size:0.78rem">Approved Days</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #f59e0b">
            <div class="fw-bold fs-4 text-warning">{{ $pendingCount }}</div>
            <div class="text-muted" style="font-size:0.78rem">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #ef4444">
            <div class="fw-bold fs-4 text-danger">{{ $rejectedCount }}</div>
            <div class="text-muted" style="font-size:0.78rem">Rejected</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-1">
                <label class="form-label mb-1" style="font-size:0.78rem">Year</label>
                <input type="number" name="year" class="form-control" value="{{ $year }}" min="2020" max="2030">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:0.78rem">Employee</label>
                <select name="employee_id" class="form-select">
                    <option value="">All Employees</option>
                    @foreach($allEmployees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id')==$emp->id ? 'selected':'' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">All Depts</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id')==$dept->id ? 'selected':'' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Leave Type</label>
                <select name="leave_type_id" class="form-select">
                    <option value="">All Types</option>
                    @foreach($leaveTypes as $lt)
                        <option value="{{ $lt->id }}" {{ request('leave_type_id')==$lt->id ? 'selected':'' }}>{{ $lt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending"  {{ request('status')==='pending'  ? 'selected':'' }}>Pending</option>
                    <option value="approved" {{ request('status')==='approved' ? 'selected':'' }}>Approved</option>
                    <option value="rejected" {{ request('status')==='rejected' ? 'selected':'' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('reports.leave') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Leave Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="width:30px;height:30px;font-size:0.75rem">{{ substr($leave->employee->name ?? 'N',0,1) }}</div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.875rem">{{ $leave->employee->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $leave->employee->employee_id ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $leave->employee->department->name ?? '—' }}</span></td>
                        <td><span class="badge bg-info-subtle text-info border border-info-subtle">{{ $leave->leaveType->name ?? 'N/A' }}</span></td>
                        <td style="font-size:0.85rem">{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                        <td style="font-size:0.85rem">{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $leave->days }}d</span></td>
                        <td>
                            @if($leave->status === 'approved')
                                <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i>Approved</span>
                            @elseif($leave->status === 'rejected')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-clock me-1"></i>Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-calendar-x d-block mb-2" style="font-size:2rem;opacity:0.25"></i>
                        No leave records found for {{ $year }}
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($leaves->count() > 0)
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">{{ $leaves->count() }} record(s) — Total {{ $leaves->sum('days') }} days</small>
    </div>
    @endif
</div>
@endsection
