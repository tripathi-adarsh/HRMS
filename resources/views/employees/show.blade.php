@extends('layouts.app')
@section('content')
<div class="page-header d-flex justify-content-between">
    <div>
        <h4>Employee Profile</h4>
        <p>Detailed information about {{ $employee->name }}</p>
    </div>
    <div>
        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary ms-2">Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center p-4">
            @if($employee->photo)
                <img src="{{ asset('storage/'.$employee->photo) }}" class="rounded-circle mx-auto mb-3" width="100" height="100" style="object-fit:cover">
            @else
                <div class="avatar mx-auto mb-3" style="width:80px;height:80px;font-size:2rem">{{ substr($employee->name,0,1) }}</div>
            @endif
            <h5 class="fw-bold">{{ $employee->name }}</h5>
            <p class="text-muted">{{ $employee->designation->name ?? 'N/A' }}</p>
            <span class="badge {{ $employee->status === 'active' ? 'bg-success' : 'bg-secondary' }} mb-3">{{ ucfirst($employee->status) }}</span>
            <div class="text-start">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Employee ID</span>
                    <code>{{ $employee->employee_id }}</code>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Department</span>
                    <span>{{ $employee->department->name ?? 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Salary</span>
                    <span class="fw-semibold">₹{{ number_format($employee->salary) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Joining Date</span>
                    <span>{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Phone</span>
                    <span>{{ $employee->phone ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <!-- Recent Attendance -->
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0 fw-bold">Recent Attendance</h6></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Date</th><th>Status</th><th>Punch In</th><th>Punch Out</th></tr></thead>
                    <tbody>
                        @forelse($employee->attendances->take(5) as $att)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</td>
                            <td>
                                @php $colors = ['present'=>'success','absent'=>'danger','late'=>'warning','half_day'=>'info','holiday'=>'secondary']; @endphp
                                <span class="badge bg-{{ $colors[$att->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$att->status)) }}</span>
                            </td>
                            <td>{{ $att->punch_in ?? '-' }}</td>
                            <td>{{ $att->punch_out ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">No attendance records</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Recent Leaves -->
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-bold">Leave History</h6></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Type</th><th>From</th><th>To</th><th>Days</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($employee->leaves->take(5) as $leave)
                        <tr>
                            <td>{{ $leave->leaveType->name ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                            <td>{{ $leave->days }}</td>
                            <td>
                                <span class="badge {{ $leave->status === 'approved' ? 'bg-success' : ($leave->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted">No leave records</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection