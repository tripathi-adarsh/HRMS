@extends('layouts.app')
@section('title', 'Attendance Report')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Attendance Report</h4>
        <p>Monthly attendance summary by employee and department</p>
    </div>
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2" style="font-size:0.82rem;border-radius:8px">
        {{ \Carbon\Carbon::create(null,$month)->format('F') }} {{ $year }}
    </span>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #10b981">
            <div class="fw-bold fs-4 text-success">{{ $totalPresent }}</div>
            <div class="text-muted" style="font-size:0.78rem">Total Present Days</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #ef4444">
            <div class="fw-bold fs-4 text-danger">{{ $totalAbsent }}</div>
            <div class="text-muted" style="font-size:0.78rem">Total Absent Days</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #f59e0b">
            <div class="fw-bold fs-4 text-warning">{{ $totalLate }}</div>
            <div class="text-muted" style="font-size:0.78rem">Total Late Days</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #06b6d4">
            <div class="fw-bold fs-4 text-info">{{ $totalHalf }}</div>
            <div class="text-muted" style="font-size:0.78rem">Total Half Days</div>
        </div>
    </div>
</div>

<div class="card">
    <!-- Filters -->
    <div class="card-header">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Month</label>
                <select name="month" class="form-select">
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" {{ $month==$m ? 'selected':'' }}>{{ \Carbon\Carbon::create(null,$m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
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
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:0.78rem">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id')==$dept->id ? 'selected':'' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('reports.attendance') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <th><span class="badge bg-success-subtle text-success border border-success-subtle">Present</span></th>
                        <th><span class="badge bg-danger-subtle text-danger border border-danger-subtle">Absent</span></th>
                        <th><span class="badge bg-warning-subtle text-warning border border-warning-subtle">Late</span></th>
                        <th><span class="badge bg-info-subtle text-info border border-info-subtle">Half Day</span></th>
                        <th><span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Holiday</span></th>
                        <th>Total</th>
                        <th>Attendance %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    @php
                        $atts    = $emp->attendances;
                        $present = $atts->where('status','present')->count();
                        $absent  = $atts->where('status','absent')->count();
                        $late    = $atts->where('status','late')->count();
                        $half    = $atts->where('status','half_day')->count();
                        $holiday = $atts->where('status','holiday')->count();
                        $total   = $atts->count();
                        $pct     = $total > 0 ? round((($present + $late + $half * 0.5) / $total) * 100) : 0;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="width:30px;height:30px;font-size:0.75rem">{{ substr($emp->name,0,1) }}</div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.875rem">{{ $emp->name }}</div>
                                    <small class="text-muted">{{ $emp->employee_id }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $emp->department->name ?? '—' }}</span></td>
                        <td><span class="fw-bold text-success">{{ $present }}</span></td>
                        <td><span class="fw-bold text-danger">{{ $absent }}</span></td>
                        <td><span class="fw-bold text-warning">{{ $late }}</span></td>
                        <td><span class="fw-bold text-info">{{ $half }}</span></td>
                        <td><span class="fw-bold text-secondary">{{ $holiday }}</span></td>
                        <td><span class="fw-semibold">{{ $total }}</span></td>
                        <td style="min-width:120px">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-fill" style="height:6px">
                                    <div class="progress-bar {{ $pct >= 80 ? 'bg-success' : ($pct >= 60 ? 'bg-warning' : 'bg-danger') }}"
                                        style="width:{{ $pct }}%"></div>
                                </div>
                                <span style="font-size:0.78rem;font-weight:600;min-width:32px">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-5">
                        <i class="bi bi-calendar-x d-block mb-2" style="font-size:2rem;opacity:0.25"></i>
                        No attendance data found for this period
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(count($employees) > 0)
    <div class="card-footer">
        <small class="text-muted">Showing {{ count($employees) }} employee(s) for {{ \Carbon\Carbon::create(null,$month)->format('F Y') }}</small>
    </div>
    @endif
</div>
@endsection
