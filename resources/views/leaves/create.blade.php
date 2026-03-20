@extends('layouts.app')
@section('content')
<div class="page-header"><h4>Apply for Leave</h4></div>
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('leaves.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Leave Type *</label>
                <select name="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                    <option value="">Select Leave Type</option>
                    @foreach($leaveTypes as $lt)
                        <option value="{{ $lt->id }}" {{ old('leave_type_id') == $lt->id ? 'selected' : '' }}>
                            {{ $lt->name }} ({{ $lt->days_allowed }} days/year)
                        </option>
                    @endforeach
                </select>
                @error('leave_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">From Date *</label>
                    <input type="date" name="from_date" class="form-control @error('from_date') is-invalid @enderror" value="{{ old('from_date') }}" required>
                    @error('from_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">To Date *</label>
                    <input type="date" name="to_date" class="form-control @error('to_date') is-invalid @enderror" value="{{ old('to_date') }}" required>
                    @error('to_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Reason *</label>
                <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="4" required>{{ old('reason') }}</textarea>
                @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit Leave Request</button>
            <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection