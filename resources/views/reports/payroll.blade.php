@extends('layouts.app')
@section('title', 'Payroll Report')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Payroll Report</h4>
        <p>Annual payroll summary with filters by employee, department and status</p>
    </div>
    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2" style="font-size:0.82rem;border-radius:8px">
        Year {{ $year }}
    </span>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #6366f1">
            <div class="fw-bold fs-4 text-primary">{{ $payrolls->count() }}</div>
            <div class="text-muted" style="font-size:0.78rem">Total Records</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #10b981">
            <div class="fw-bold fs-4 text-success">&#8377;{{ number_format($totalNet/1000,1) }}K</div>
            <div class="text-muted" style="font-size:0.78rem">Total Net Paid</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #f59e0b">
            <div class="fw-bold fs-4 text-warning">&#8377;{{ number_format($totalBonus/1000,1) }}K</div>
            <div class="text-muted" style="font-size:0.78rem">Total Bonus</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center" style="border-left:4px solid #10b981">
            <div class="fw-bold fs-4 text-success">{{ $paidCount }}</div>
            <div class="text-muted" style="font-size:0.78rem">Paid Records</div>
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
            <div class="col-md-2">
                <label class="form-label mb-1" style="font-size:0.78rem">Month</label>
                <select name="month" class="form-select">
                    <option value="">All Months</option>
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" {{ $month==$m ? 'selected':'' }}>{{ \Carbon\Carbon::create(null,$m)->format('F') }}</option>
                    @endfor
                </select>
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
                <label class="form-label mb-1" style="font-size:0.78rem">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="paid"    {{ request('status')==='paid'    ? 'selected':'' }}>Paid</option>
                    <option value="pending" {{ request('status')==='pending' ? 'selected':'' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('reports.payroll') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <th>Month</th>
                        <th>Basic</th>
                        <th>Bonus</th>
                        <th>Deduction</th>
                        <th>Net Salary</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="width:30px;height:30px;font-size:0.75rem">{{ substr($p->employee->name ?? 'N',0,1) }}</div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.875rem">{{ $p->employee->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $p->employee->employee_id ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $p->employee->department->name ?? '—' }}</span></td>
                        <td style="font-size:0.85rem">{{ \Carbon\Carbon::create($p->year, $p->month)->format('M Y') }}</td>
                        <td style="font-size:0.85rem">&#8377;{{ number_format($p->basic_salary) }}</td>
                        <td class="text-success fw-semibold" style="font-size:0.85rem">
                            @if($p->bonus > 0)+&#8377;{{ number_format($p->bonus) }}@else <span class="text-muted">—</span>@endif
                        </td>
                        <td class="text-danger fw-semibold" style="font-size:0.85rem">-&#8377;{{ number_format($p->deduction) }}</td>
                        <td class="fw-bold">&#8377;{{ number_format($p->net_salary) }}</td>
                        <td>
                            <span class="badge {{ $p->status === 'paid' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' }}">
                                <i class="bi bi-circle-fill me-1" style="font-size:0.4rem"></i>{{ ucfirst($p->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-cash-stack d-block mb-2" style="font-size:2rem;opacity:0.25"></i>
                        No payroll records found
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payrolls->count() > 0)
    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">{{ $payrolls->count() }} record(s)</small>
        <small class="fw-semibold">Total Net: &#8377;{{ number_format($totalNet) }}</small>
    </div>
    @endif
</div>
@endsection
