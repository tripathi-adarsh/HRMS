<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->employee->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Arial', sans-serif; background: #f8f9fa; }
        .payslip { max-width: 700px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .payslip-header { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; padding: 30px; }
        .payslip-body { padding: 30px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
        .salary-row { display: flex; justify-content: space-between; padding: 10px 0; }
        .total-row { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 10px; }
        @media print { body { background: #fff; } .payslip { box-shadow: none; } .no-print { display: none; } }
    </style>
</head>
<body>
<div class="payslip">
    <div class="payslip-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h4 class="mb-1">HRMS Pro</h4>
                <p class="mb-0 opacity-75">Salary Slip</p>
            </div>
            <div class="text-end">
                <h5 class="mb-1">{{ \Carbon\Carbon::create(null, $payroll->month)->format('F') }} {{ $payroll->year }}</h5>
                <span class="badge bg-light text-dark">{{ ucfirst($payroll->status) }}</span>
            </div>
        </div>
    </div>
    <div class="payslip-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted mb-2">Employee Details</h6>
                <div class="info-row"><span class="text-muted">Name</span><strong>{{ $payroll->employee->name }}</strong></div>
                <div class="info-row"><span class="text-muted">Employee ID</span><code>{{ $payroll->employee->employee_id }}</code></div>
                <div class="info-row"><span class="text-muted">Department</span>{{ $payroll->employee->department->name ?? 'N/A' }}</div>
                <div class="info-row"><span class="text-muted">Designation</span>{{ $payroll->employee->designation->name ?? 'N/A' }}</div>
            </div>
        </div>
        <h6 class="text-muted mb-3">Salary Breakdown</h6>
        <div class="salary-row"><span>Basic Salary</span><span>₹{{ number_format($payroll->basic_salary, 2) }}</span></div>
        <div class="salary-row text-success"><span>Bonus</span><span>+ ₹{{ number_format($payroll->bonus, 2) }}</span></div>
        <div class="salary-row text-danger"><span>Deduction</span><span>- ₹{{ number_format($payroll->deduction, 2) }}</span></div>
        <div class="total-row d-flex justify-content-between">
            <strong>Net Salary</strong>
            <strong class="text-primary fs-5">₹{{ number_format($payroll->net_salary, 2) }}</strong>
        </div>
        <div class="mt-4 text-center no-print">
            <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Print Payslip</button>
            <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary ms-2">Back</a>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>