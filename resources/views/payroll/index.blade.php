@extends('layouts.app')
@section('title', 'Payroll')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Payroll Management</h4>
        <p>Generate and manage monthly employee payroll</p>
    </div>
    <form method="POST" action="{{ route('payroll.generate') }}">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="year" value="{{ $year }}">
        <button type="submit" class="btn btn-primary" onclick="return confirm('Generate payroll for {{ \Carbon\Carbon::create(null,$month)->format('F') }} {{ $year }}?')">
            <i class="bi bi-lightning-fill me-1"></i> Generate Payroll
        </button>
    </form>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-primary">{{ $payrolls->total() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Total Records</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-success">{{ $payrolls->where('status','paid')->count() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Paid</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-warning">{{ $payrolls->where('status','pending')->count() }}</div>
            <div class="text-muted" style="font-size:0.8rem">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="fw-bold fs-4 text-danger">&#8377;{{ number_format($payrolls->sum('net_salary')/1000, 1) }}K</div>
            <div class="text-muted" style="font-size:0.8rem">Total Payout</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Month</label>
                <select name="month" class="form-select">
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null,$m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Year</label>
                <input type="number" name="year" class="form-control" value="{{ $year }}" min="2020" max="2030">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:0.78rem">Search Employee</label>
                <input type="text" name="search" class="form-control" placeholder="Name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i> Filter</button>
                <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <th>Basic Salary</th>
                        <th>Bonus</th>
                        <th>Deduction</th>
                        <th>Net Salary</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="width:32px;height:32px;font-size:0.8rem">
                                    {{ substr($payroll->employee->name ?? 'N', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.875rem">{{ $payroll->employee->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $payroll->employee->employee_id ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($payroll->employee->department)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $payroll->employee->department->name }}</span>
                            @else —
                            @endif
                        </td>
                        <td>&#8377;{{ number_format($payroll->basic_salary) }}</td>
                        <td class="text-success fw-semibold">
                            @if($payroll->bonus > 0)+&#8377;{{ number_format($payroll->bonus) }}@else <span class="text-muted">—</span>@endif
                        </td>
                        <td class="text-danger fw-semibold">-&#8377;{{ number_format($payroll->deduction) }}</td>
                        <td class="fw-bold" style="font-size:0.95rem">&#8377;{{ number_format($payroll->net_salary) }}</td>
                        <td style="font-size:0.82rem;color:#64748b">
                            {{ \Carbon\Carbon::create($payroll->year, $payroll->month)->format('M Y') }}
                        </td>
                        <td>
                            <span class="badge {{ $payroll->status === 'paid' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }}">
                                <i class="bi bi-circle-fill me-1" style="font-size:0.45rem"></i>{{ ucfirst($payroll->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('payroll.edit', $payroll) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('payroll.payslip', $payroll) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Payslip"><i class="bi bi-file-earmark-pdf"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-cash-stack d-block mb-2" style="font-size:2.5rem;opacity:0.25"></i>
                            No payroll records for this period. Click "Generate Payroll" to create.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payrolls->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $payrolls->firstItem() }}–{{ $payrolls->lastItem() }} of {{ $payrolls->total() }} records</small>
        {{ $payrolls->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
