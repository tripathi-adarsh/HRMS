@extends('layouts.app')
@section('content')
<div class="page-header"><h4>Edit Designation</h4></div>
<div class="card" style="max-width:500px">
    <div class="card-body">
        <form method="POST" action="{{ route('designations.update', $designation) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Designation Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $designation->name) }}" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Department *</label>
                <select name="department_id" class="form-select" required>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $designation->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('designations.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection