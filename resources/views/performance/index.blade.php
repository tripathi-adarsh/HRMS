@extends('layouts.app')
@section('title', 'Performance')
@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Performance Management</h4>
        <p>Track tasks, ratings and employee performance reviews</p>
    </div>
    @if(auth()->user()->role !== 'employee')
    <a href="{{ route('performance.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Assign Task
    </a>
    @endif
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="mb-1" style="font-size:1.5rem;color:#6366f1"><i class="bi bi-clipboard-check-fill"></i></div>
            <div class="fw-bold fs-5">{{ $performances->total() }}</div>
            <div class="text-muted" style="font-size:0.78rem">Total Tasks</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="mb-1" style="font-size:1.5rem;color:#10b981"><i class="bi bi-check-circle-fill"></i></div>
            <div class="fw-bold fs-5 text-success">{{ $performances->where('status','completed')->count() }}</div>
            <div class="text-muted" style="font-size:0.78rem">Completed</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="mb-1" style="font-size:1.5rem;color:#f59e0b"><i class="bi bi-hourglass-split"></i></div>
            <div class="fw-bold fs-5 text-warning">{{ $performances->where('status','in_progress')->count() }}</div>
            <div class="text-muted" style="font-size:0.78rem">In Progress</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 text-center">
            <div class="mb-1" style="font-size:1.5rem;color:#ef4444"><i class="bi bi-hourglass"></i></div>
            <div class="fw-bold fs-5 text-danger">{{ $performances->where('status','assigned')->count() }}</div>
            <div class="text-muted" style="font-size:0.78rem">Assigned</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1" style="font-size:0.78rem">Search</label>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.85rem"></i>
                    <input type="text" name="search" class="form-control ps-4" placeholder="Employee or task..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:0.78rem">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="assigned"    {{ request('status') === 'assigned'    ? 'selected' : '' }}>Assigned</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed"   {{ request('status') === 'completed'   ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:0.78rem">Rating</label>
                <select name="rating" class="form-select">
                    <option value="">All Ratings</option>
                    @for($r=5;$r>=1;$r--)
                    <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }} Star{{ $r > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('performance.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Review Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($performances as $perf)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar" style="width:32px;height:32px;font-size:0.8rem">
                                    {{ substr($perf->employee->name ?? 'N', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.875rem">{{ $perf->employee->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $perf->employee->department->name ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td style="max-width:200px">
                            <div class="fw-semibold" style="font-size:0.875rem">{{ $perf->task }}</div>
                            @if($perf->description)
                            <small class="text-muted">{{ \Illuminate\Support\Str::limit($perf->description, 45) }}</small>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusMap = [
                                    'assigned'    => ['bg-primary-subtle text-primary border-primary-subtle',   'bi-clipboard',       'Assigned'],
                                    'in_progress' => ['bg-warning-subtle text-warning border-warning-subtle',   'bi-hourglass-split', 'In Progress'],
                                    'completed'   => ['bg-success-subtle text-success border-success-subtle',   'bi-check-circle',    'Completed'],
                                ];
                                [$cls, $icon, $label] = $statusMap[$perf->status] ?? ['bg-secondary-subtle text-secondary border-secondary-subtle','bi-dash','Unknown'];
                            @endphp
                            <span class="badge {{ $cls }} border">
                                <i class="bi {{ $icon }} me-1"></i>{{ $label }}
                            </span>
                        </td>
                        <td>
                            @if($perf->rating)
                            <div class="d-flex align-items-center gap-1">
                                @for($i=1;$i<=5;$i++)
                                    <i class="bi bi-star{{ $i <= $perf->rating ? '-fill' : '' }}" style="color:{{ $i <= $perf->rating ? '#f59e0b' : '#d1d5db' }};font-size:0.85rem"></i>
                                @endfor
                                <span class="ms-1 text-muted" style="font-size:0.78rem">({{ $perf->rating }}/5)</span>
                            </div>
                            @else
                            <span class="text-muted" style="font-size:0.82rem">Not rated</span>
                            @endif
                        </td>
                        <td style="max-width:160px;font-size:0.82rem">
                            {{ $perf->review ? \Illuminate\Support\Str::limit($perf->review, 40) : '—' }}
                        </td>
                        <td style="font-size:0.82rem;color:#64748b">
                            {{ $perf->review_date ? \Carbon\Carbon::parse($perf->review_date)->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('performance.edit', $perf) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(auth()->user()->role !== 'employee')
                                <form method="POST" action="{{ route('performance.destroy', $perf) }}" class="d-inline" onsubmit="return confirm('Delete this task?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-graph-up d-block mb-2" style="font-size:2.5rem;opacity:0.25"></i>
                            No performance records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($performances->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">Showing {{ $performances->firstItem() }}–{{ $performances->lastItem() }} of {{ $performances->total() }} records</small>
        {{ $performances->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
