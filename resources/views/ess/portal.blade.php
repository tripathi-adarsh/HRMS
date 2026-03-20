@extends('layouts.app')
@section('title', 'My Portal')
@section('content')

@php
    $now = \Carbon\Carbon::now();
    $punchInTime  = ($todayAtt && $todayAtt->punch_in)  ? \Carbon\Carbon::parse($todayAtt->punch_in)->format('h:i A')  : null;
    $punchOutTime = ($todayAtt && $todayAtt->punch_out) ? \Carbon\Carbon::parse($todayAtt->punch_out)->format('h:i A') : null;
    $hoursWorked  = ($todayAtt && $todayAtt->punch_in && $todayAtt->punch_out)
        ? round(\Carbon\Carbon::parse($todayAtt->punch_in)->diffInMinutes(\Carbon\Carbon::parse($todayAtt->punch_out)) / 60, 1)
        : null;
    $attPct = $attSummary['total'] > 0
        ? round((($attSummary['present'] + $attSummary['late'] + $attSummary['half_day'] * 0.5) / $attSummary['total']) * 100)
        : 0;
@endphp

{{-- Page header --}}
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Welcome, {{ $emp->name }} 👋</h4>
        <p>{{ $emp->designation->name ?? 'Employee' }} &mdash; {{ $emp->department->name ?? '' }} &mdash; {{ $now->format('l, d M Y') }}</p>
    </div>
    <span class="badge px-3 py-2 border" style="font-size:0.82rem;border-radius:8px;
        {{ ($todayAtt && $todayAtt->punch_in && !$todayAtt->punch_out)
            ? 'background:rgba(16,185,129,0.12);color:#10b981;border-color:rgba(16,185,129,0.3)'
            : 'background:rgba(99,102,241,0.1);color:#6366f1;border-color:rgba(99,102,241,0.25)' }}">
        <i class="bi bi-circle-fill me-1" style="font-size:0.45rem"></i>
        {{ ($todayAtt && $todayAtt->punch_in && !$todayAtt->punch_out) ? 'Currently Working' : 'Not Checked In' }}
    </span>
</div>

{{-- Punch In/Out Card --}}
<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <div class="card h-100" style="background:linear-gradient(135deg,#6366f1,#a855f7);border:none">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div>
                        <div style="color:rgba(255,255,255,0.8);font-size:0.8rem">Today's Attendance</div>
                        <div style="color:#fff;font-size:1.1rem;font-weight:700" id="liveClock">{{ $now->format('h:i A') }}</div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,0.12);border-radius:10px;padding:10px;text-align:center">
                            <div style="color:rgba(255,255,255,0.7);font-size:0.72rem">Punch In</div>
                            <div style="color:#fff;font-weight:700;font-size:0.95rem">{{ $punchInTime ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,0.12);border-radius:10px;padding:10px;text-align:center">
                            <div style="color:rgba(255,255,255,0.7);font-size:0.72rem">Punch Out</div>
                            <div style="color:#fff;font-weight:700;font-size:0.95rem">{{ $punchOutTime ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                @if($hoursWorked)
                <div class="text-center mb-3" style="color:rgba(255,255,255,0.85);font-size:0.85rem">
                    <i class="bi bi-check-circle-fill me-1"></i>{{ $hoursWorked }} hrs worked today
                </div>
                @endif

                <div class="d-flex gap-2">
                    @if(!$todayAtt || !$todayAtt->punch_in)
                    <form method="POST" action="{{ route('ess.punchIn') }}" class="flex-fill">
                        @csrf
                        <button type="submit" class="btn w-100 fw-bold"
                            style="background:#fff;color:#6366f1;border-radius:10px;padding:10px">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Punch In
                        </button>
                    </form>
                    @elseif($todayAtt && !$todayAtt->punch_out)
                    <form method="POST" action="{{ route('ess.punchOut') }}" class="flex-fill">
                        @csrf
                        <button type="submit" class="btn w-100 fw-bold"
                            style="background:rgba(255,255,255,0.2);color:#fff;border:1.5px solid rgba(255,255,255,0.4);border-radius:10px;padding:10px">
                            <i class="bi bi-box-arrow-right me-1"></i>Punch Out
                        </button>
                    </form>
                    @else
                    <div class="w-100 text-center py-2" style="color:rgba(255,255,255,0.8);font-size:0.85rem">
                        <i class="bi bi-check-circle-fill me-1"></i>Attendance marked for today
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- This month attendance summary --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2 text-primary"></i>{{ $now->format('F Y') }} Attendance</h6>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $attSummary['total'] }} days recorded</span>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    @foreach([
                        ['present',  'Present',  '#10b981', 'bi-person-check-fill'],
                        ['absent',   'Absent',   '#ef4444', 'bi-person-x-fill'],
                        ['late',     'Late',     '#f59e0b', 'bi-clock-fill'],
                        ['half_day', 'Half Day', '#06b6d4', 'bi-circle-half'],
                    ] as [$key, $label, $color, $icon])
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 text-center" style="background:{{ $color }}18;border:1px solid {{ $color }}30">
                            <i class="bi {{ $icon }} mb-1" style="font-size:1.3rem;color:{{ $color }}"></i>
                            <div class="fw-bold fs-5" style="color:{{ $color }}">{{ $attSummary[$key] }}</div>
                            <div class="text-muted" style="font-size:0.75rem">{{ $label }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="flex-fill">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Attendance Rate</small>
                            <small class="fw-bold" style="color:{{ $attPct >= 80 ? '#10b981' : ($attPct >= 60 ? '#f59e0b' : '#ef4444') }}">{{ $attPct }}%</small>
                        </div>
                        <div class="progress" style="height:8px;border-radius:10px">
                            <div class="progress-bar" style="width:{{ $attPct }}%;background:{{ $attPct >= 80 ? '#10b981' : ($attPct >= 60 ? '#f59e0b' : '#ef4444') }};border-radius:10px"></div>
                        </div>
                    </div>
                    <div class="text-center" style="min-width:80px">
                        <div class="fw-bold" style="font-size:1.1rem">{{ $workedDays }}/{{ $workingDays }}</div>
                        <div class="text-muted" style="font-size:0.72rem">Working Days</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Salary Section --}}
<div class="row g-3 mb-3">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-cash-stack me-2 text-success"></i>Salary Overview</h6>
                <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $now->format('M Y') }}</span>
            </div>
            <div class="card-body">
                {{-- CTC --}}
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted" style="font-size:0.85rem">Monthly CTC</span>
                    <span class="fw-bold">&#8377;{{ number_format($emp->salary) }}</span>
                </div>
                {{-- Estimated in-hand --}}
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted" style="font-size:0.85rem">Est. In-Hand (pro-rated)</span>
                    <span class="fw-bold text-success">&#8377;{{ number_format($estimatedSalary) }}</span>
                </div>
                {{-- Payroll record --}}
                @if($payroll)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted" style="font-size:0.85rem">Basic Salary</span>
                    <span class="fw-semibold">&#8377;{{ number_format($payroll->basic_salary) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted" style="font-size:0.85rem">Bonus</span>
                    <span class="fw-semibold text-success">+&#8377;{{ number_format($payroll->bonus) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted" style="font-size:0.85rem">Deductions</span>
                    <span class="fw-semibold text-danger">-&#8377;{{ number_format($payroll->deduction) }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2">
                    <span class="fw-bold">Net Salary</span>
                    <span class="fw-bold fs-5 text-primary">&#8377;{{ number_format($payroll->net_salary) }}</span>
                </div>
                <div class="mt-2">
                    <span class="badge {{ $payroll->status === 'paid' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }}">
                        <i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ ucfirst($payroll->status) }}
                    </span>
                </div>
                @else
                <div class="text-center text-muted py-3" style="font-size:0.85rem">
                    <i class="bi bi-hourglass d-block mb-1" style="font-size:1.5rem;opacity:0.3"></i>
                    Payroll not generated yet for {{ $now->format('F Y') }}
                </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('salary.calculator') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-calculator me-1"></i>Open Salary Calculator
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Payroll history --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Payroll History</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Basic</th>
                            <th>Bonus</th>
                            <th>Net Salary</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayrolls as $p)
                        <tr>
                            <td style="font-size:0.85rem">{{ \Carbon\Carbon::create($p->year, $p->month)->format('M Y') }}</td>
                            <td style="font-size:0.85rem">&#8377;{{ number_format($p->basic_salary) }}</td>
                            <td class="text-success" style="font-size:0.85rem">
                                {{ $p->bonus > 0 ? '+&#8377;'.number_format($p->bonus) : '—' }}
                            </td>
                            <td class="fw-bold" style="font-size:0.875rem">&#8377;{{ number_format($p->net_salary) }}</td>
                            <td>
                                <span class="badge {{ $p->status === 'paid' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4" style="font-size:0.85rem">No payroll records yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Leave Section --}}
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-x me-2 text-warning"></i>Leave Summary {{ $year }}</h6>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background:#10b98118;border:1px solid #10b98130">
                            <div class="fw-bold fs-4 text-success">{{ $leaveSummary['approved'] }}</div>
                            <div class="text-muted" style="font-size:0.75rem">Days Approved</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-3 text-center" style="background:#f59e0b18;border:1px solid #f59e0b30">
                            <div class="fw-bold fs-4 text-warning">{{ $leaveSummary['pending'] }}</div>
                            <div class="text-muted" style="font-size:0.75rem">Pending</div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('leaves.create') }}" class="btn btn-primary w-100">
                    <i class="bi bi-plus-lg me-1"></i>Apply for Leave
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>Recent Leave Requests</h6>
                <a href="{{ route('my.leaves') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves->take(5) as $leave)
                        <tr>
                            <td>
                                <span class="badge bg-info-subtle text-info border border-info-subtle">
                                    {{ $leave->leaveType->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td style="font-size:0.85rem">{{ \Carbon\Carbon::parse($leave->from_date)->format('d M') }}</td>
                            <td style="font-size:0.85rem">{{ \Carbon\Carbon::parse($leave->to_date)->format('d M') }}</td>
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
                        <tr><td colspan="5" class="text-center text-muted py-4" style="font-size:0.85rem">No leave requests this year</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Live clock
function updateClock() {
    const now = new Date();
    const h = now.getHours();
    const m = String(now.getMinutes()).padStart(2,'0');
    const s = String(now.getSeconds()).padStart(2,'0');
    const ampm = h >= 12 ? 'PM' : 'AM';
    const h12 = h % 12 || 12;
    const el = document.getElementById('liveClock');
    if (el) el.textContent = `${h12}:${m}:${s} ${ampm}`;
}
setInterval(updateClock, 1000);
updateClock();
</script>
@endpush
@endsection
