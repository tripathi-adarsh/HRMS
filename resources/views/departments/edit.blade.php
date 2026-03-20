@extends('layouts.app')
@section('content')
<div class="page-header"><h4>Edit Department</h4></div>
<div class="card" style="max-width:600px">
    <div class="card-body">
        <form method="POST" action="{{ route('departments.update', $department) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Department Name *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $department->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $department->description) }}</textarea>
            </div>
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ $department->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="isActive">Active</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Department</button>
            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection