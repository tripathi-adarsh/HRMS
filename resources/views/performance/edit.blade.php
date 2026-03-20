@extends('layouts.app')
@section('content')
<div class="page-header"><h4>Update Performance</h4></div>
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('performance.update', $performance) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Employee</label>
                <input type="text" class="form-control" value="{{ $performance->employee->name ?? 'N/A' }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Task *</label>
                <input type="text" name="task" class="form-control" value="{{ old('task', $performance->task) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $performance->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="assigned" {{ $performance->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="in_progress" {{ $performance->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $performance->status === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Rating (1-5)</label>
                <select name="rating" class="form-select">
                    <option value="">No Rating</option>
                    @for($i=1;$i<=5;$i++)
                        <option value="{{ $i }}" {{ $performance->rating == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Review</label>
                <textarea name="review" class="form-control" rows="3">{{ old('review', $performance->review) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Review Date</label>
                <input type="date" name="review_date" class="form-control" value="{{ old('review_date', $performance->review_date) }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection