@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Dashboard</h4>
        <p>Welcome back, {{ auth()->user()->name }}! Here's your HR overview for today.</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2" style="font-size:0.8rem;border-radius:8px">
            <i class="bi bi-circle-fill me-1" style="font-size:0.5rem"></i>
            {{ \Carbon\Carbon::now()->format('l, d M Y') }}
        </span>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4f46e5, #7c3aed)">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <h3>{{ $totalEmployees }}</h3>
            <p>Total Employees</p>
            <div style="font-size:0.75rem;opacity:0.75;margin-top:6px"><i class="bi bi-arrow-up-short"></i> Active workforce</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #059669, #10b981)">
            <div class="stat-icon"><i class="bi bi-person-check-fill"></i></div>
            <h3>{{ $todayPresent }}</h3>
            <p>Present Today</p>
            <div style="font-size:0.75rem;opacity:0.75;margin-top:6px">
                @if($totalEmployees > 0)
                    {{ round(($todayPresent / $totalEmployees) * 100) }}% attendance rate
                @else
                    No data
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #d97706, #f59e0b)">
            <div class="stat-icon"><i class="bi bi-calendar-x-fill"></i></div>
            <h3>{{ $onLeave }}</h3>
            <p>On Leave Today</p>
            <div style="font-size:0.75rem;opacity:0.75;margin-top:6px"><i class="bi bi-clock"></i> Approved leaves</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #dc2626, #ef4444)">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <h3>&#8377;{{ number_format($monthlySalary/1000, 1) }}K</h3>
            <p>Monthly Payroll</p>
            <div style="font-size:0.75rem;opacity:0.75;margin-top:6px">{{ \Carbon\Carbon::now()->format('F Y') }}</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <!-- Attendance Chart -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">Today's Attendance Overview</h6>
                    <small class="text-muted">{{ \Carbon\Carbon::today()->format('d M Y') }}</small>
                </div>
                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="90"></canvas>
            </div>
        </div>
    </div>
    <!-- Department Breakdown -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-bold">Employees by Department</h6>
                <small class="text-muted">Headcount distribution</small>
            </div>
            <div class="card-body d-flex flex-column justify-content-center">
                <canvas id="deptChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Quick Stats Row -->
    <div class="col-12">
        <div class="row g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center p-3">
                    <div class="mb-2" style="font-size:1.8rem;color:#4f46e5"><i class="bi bi-hourglass-split"></i></div>
                    <div class="fw-bold fs-5">{{ $pendingLeaves ?? 0 }}</div>
                    <div class="text-muted" style="font-size:0.8rem">Pending Leaves</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center p-3">
                    <div class="mb-2" style="font-size:1.8rem;color:#10b981"><i class="bi bi-person-plus-fill"></i></div>
                    <div class="fw-bold fs-5">{{ $newJoinees ?? 0 }}</div>
                    <div class="text-muted" style="font-size:0.8rem">New This Month</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center p-3">
                    <div class="mb-2" style="font-size:1.8rem;color:#f59e0b"><i class="bi bi-building"></i></div>
                    <div class="fw-bold fs-5">{{ $departments->count() }}</div>
                    <div class="text-muted" style="font-size:0.8rem">Departments</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card text-center p-3">
                    <div class="mb-2" style="font-size:1.8rem;color:#ef4444"><i class="bi bi-person-x-fill"></i></div>
                    <div class="fw-bold fs-5">{{ $absentToday ?? 0 }}</div>
                    <div class="text-muted" style="font-size:0.8rem">Absent Today</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Leave Requests -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">Recent Leave Requests</h6>
                    <small class="text-muted">Latest pending and recent decisions</small>
                </div>
                <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-primary">View All</a>
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
                                <th>Status</th>
                                @if(auth()->user()->role !== 'employee')<th>Action</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeaves as $leave)
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
                                <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $leave->days }} day{{ $leave->days > 1 ? 's' : '' }}</span></td>
                                <td>
                                    @if($leave->status === 'approved')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Approved</span>
                                    @elseif($leave->status === 'rejected')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Rejected</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pending</span>
                                    @endif
                                </td>
                                @if(auth()->user()->role !== 'employee')
                                <td>
                                    @if($leave->status === 'pending')
                                    <form method="POST" action="{{ route('leaves.approve', $leave) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="Approve"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form method="POST" action="{{ route('leaves.reject', $leave) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                    @else
                                    <span class="text-muted" style="font-size:0.8rem">—</span>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-calendar-x d-block mb-2" style="font-size:2rem;opacity:0.3"></i>
                                No leave requests found
                            </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const attendanceData = @json($attendanceStats);
new Chart(document.getElementById('attendanceChart'), {
    type: 'bar',
    data: {
        labels: ['Present', 'Absent', 'Late', 'Half Day', 'Holiday'],
        datasets: [{
            label: 'Employees',
            data: [
                attendanceData.present || 0,
                attendanceData.absent || 0,
                attendanceData.late || 0,
                attendanceData.half_day || 0,
                attendanceData.holiday || 0,
            ],
            backgroundColor: ['#10b981','#ef4444','#f59e0b','#3b82f6','#8b5cf6'],
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

const depts = @json($departments);
new Chart(document.getElementById('deptChart'), {
    type: 'doughnut',
    data: {
        labels: depts.map(d => d.name),
        datasets: [{
            data: depts.map(d => d.employees_count),
            backgroundColor: ['#4f46e5','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899','#84cc16'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { padding: 12, font: { size: 11 } } } },
        cutout: '65%',
    }
});
</script>
@endpush
