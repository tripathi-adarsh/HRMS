@extends('layouts.app')
@section('content')
<div class="page-header"><h4>Edit Payroll</h4></div>
<div class="card" style="max-width:500px">
    <div class="card-body">
        <div class="mb-3 p-3 bg-light rounded">
            <strong>{{ $payroll->employee->name }}</strong><br>
            <small class="text-muted">{{ \Carbon\Carbon::create(null, $payroll->month)->format('F') }} {{ $payroll->year }}</small>
        </div>
        <form method="POST" action="{{ route('payroll.update', $payroll) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Basic Salary</label>
                <input type="number" class="form-control" value="{{ $payroll->basic_salary }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Bonus (₹)</label>
                <input type="number" name="bonus" class="form-control" value="{{ old('bonus', $payroll->bonus) }}" min="0" step="0.01">
            </div>
            <div class="mb-3">
                <label class="form-label">Deduction (₹)</label>
                <input type="number" name="deduction" class="form-control" value="{{ old('deduction', $payroll->deduction) }}" min="0" step="0.01">
            </div>
            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ $payroll->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $payroll->status === 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Payroll</button>
            <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection