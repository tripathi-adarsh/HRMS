@extends('layouts.app')
@section('title', 'Attendance')
@section('content')

<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-2">
    <div>
        <h4>Attendance Management</h4>
        <p>Mark and track daily employee attendance</p>
    </div>
    <a href="{{ route('attendance.calendar') }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-calendar3 me-1"></i> Calendar View
    </a>
</div>

<div class="card">

    {{-- Filters --}}
    <div class="card-header">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:0.78rem">Date</label>
                <input type="date" name="date" class="form-control"
                    value="{{ $date }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:0.78rem">Department</label>
                <select name="department_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}"
                            {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1" style="font-size:0.78rem">Search Employee</label>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute"
                        style="left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.85rem"></i>
                    <input type="text" name="search" class="form-control ps-4"
                        placeholder="Name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Live count bar --}}
    <div class="px-4 py-2 border-bottom d-flex gap-3 flex-wrap align-items-center"
        style="background:rgba(99,102,241,0.03)">
        <span style="font-size:0.8rem;color:#64748b">Live count:</span>
        <span class="d-flex align-items-center gap-1" style="font-size:0.82rem">
            <span style="width:8px;height:8px;border-radius:2px;background:#10b981;display:inline-block"></span>
            Present: <strong id="cP">0</strong>
        </span>
        <span class="d-flex align-items-center gap-1" style="font-size:0.82rem">
            <span style="width:8px;height:8px;border-radius:2px;background:#ef4444;display:inline-block"></span>
            Absent: <strong id="cA">0</strong>
        </span>
        <span class="d-flex align-items-center gap-1" style="font-size:0.82rem">
            <span style="width:8px;height:8px;border-radius:2px;background:#f59e0b;display:inline-block"></span>
            Late: <strong id="cL">0</strong>
        </span>
        <span class="d-flex align-items-center gap-1" style="font-size:0.82rem">
            <span style="width:8px;height:8px;border-radius:2px;background:#06b6d4;display:inline-block"></span>
            Half Day: <strong id="cH">0</strong>
        </span>
        <span class="d-flex align-items-center gap-1" style="font-size:0.82rem">
            <span style="width:8px;height:8px;border-radius:2px;background:#8b5cf6;display:inline-block"></span>
            Holiday: <strong id="cHo">0</strong>
        </span>
        <div class="ms-auto d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-success" onclick="markAll('present')">
                <i class="bi bi-check-all me-1"></i>All Present
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="markAll('absent')">
                <i class="bi bi-x-circle me-1"></i>All Absent
            </button>
        </div>
    </div>

    {{-- Attendance form --}}
    <form method="POST" action="{{ route('attendance.bulk') }}">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Punch In</th>
                            <th>Punch Out</th>
                            <th style="min-width:380px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                        @php
                            $ex  = $attendances->firstWhere('employee_id', $emp->id);
                            $cur = $ex ? $ex->status : 'absent';
                            $pIn  = ($ex && $ex->punch_in)  ? \Carbon\Carbon::parse($ex->punch_in)->format('H:i')  : '';
                            $pOut = ($ex && $ex->punch_out) ? \Carbon\Carbon::parse($ex->punch_out)->format('H:i') : '';
                        @endphp
                        <tr class="att-row" data-status="{{ $cur }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar" style="width:34px;height:34px;font-size:0.82rem">
                                        {{ substr($emp->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="font-size:0.875rem">{{ $emp->name }}</div>
                                        <small class="text-muted">{{ $emp->employee_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                    style="font-size:0.72rem">
                                    {{ $emp->department->name ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <input type="time" name="punch_in[{{ $emp->id }}]"
                                    class="form-control form-control-sm" style="width:110px"
                                    value="{{ $pIn }}">
                            </td>
                            <td>
                                <input type="time" name="punch_out[{{ $emp->id }}]"
                                    class="form-control form-control-sm" style="width:110px"
                                    value="{{ $pOut }}">
                            </td>
                            <td>
                                <input type="hidden" name="status[{{ $emp->id }}]"
                                    class="status-val" value="{{ $cur }}">
                                <div class="d-flex gap-1 flex-wrap">
                                    @php
                                        $statuses = [
                                            ['present',  'Present',  '#10b981', 'bi-person-check-fill'],
                                            ['absent',   'Absent',   '#ef4444', 'bi-person-x-fill'],
                                            ['late',     'Late',     '#f59e0b', 'bi-clock-fill'],
                                            ['half_day', 'Half Day', '#06b6d4', 'bi-circle-half'],
                                            ['holiday',  'Holiday',  '#8b5cf6', 'bi-umbrella-fill'],
                                        ];
                                    @endphp
                                    @foreach($statuses as [$v, $lbl, $clr, $ico])
                                    <button type="button"
                                        class="att-btn {{ $cur === $v ? 'att-active' : '' }}"
                                        data-val="{{ $v }}"
                                        style="--c:{{ $clr }}"
                                        onclick="setStatus(this)">
                                        <i class="bi {{ $ico }}"></i>{{ $lbl }}
                                    </button>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-people d-block mb-2" style="font-size:2rem;opacity:0.25"></i>
                                No employees found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                <i class="bi bi-calendar-event me-1"></i>
                Marking for
                <strong>{{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</strong>
                &mdash; {{ count($employees) }} employee(s)
            </small>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-lg me-2"></i>Save Attendance
            </button>
        </div>
    </form>
</div>

<style>
.att-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px; border-radius: 8px;
    border: 1.5px solid #e2e8f0;
    background: #fff; color: #64748b;
    font-size: 0.75rem; font-weight: 500;
    cursor: pointer; transition: all 0.15s; white-space: nowrap;
}
.att-btn:hover {
    border-color: var(--c);
    color: var(--c);
    background: color-mix(in srgb, var(--c) 8%, white);
}
.att-btn.att-active {
    background: color-mix(in srgb, var(--c) 12%, white);
    border-color: var(--c);
    color: var(--c);
    font-weight: 600;
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--c) 15%, transparent);
}
.att-btn i { font-size: 0.85rem; }
[data-theme="dark"] .att-btn {
    background: #1e293b; border-color: #334155; color: #94a3b8;
}
[data-theme="dark"] .att-btn:hover {
    background: color-mix(in srgb, var(--c) 15%, #1e293b);
    border-color: var(--c); color: var(--c);
}
[data-theme="dark"] .att-btn.att-active {
    background: color-mix(in srgb, var(--c) 20%, #1e293b);
    border-color: var(--c); color: var(--c);
}
.att-row[data-status="present"] td:first-child { border-left: 3px solid #10b981; }
.att-row[data-status="absent"]  td:first-child { border-left: 3px solid #ef4444; }
.att-row[data-status="late"]    td:first-child { border-left: 3px solid #f59e0b; }
.att-row[data-status="half_day"] td:first-child { border-left: 3px solid #06b6d4; }
.att-row[data-status="holiday"] td:first-child { border-left: 3px solid #8b5cf6; }
</style>

@push('scripts')
<script>
function setStatus(btn) {
    const row = btn.closest('tr');
    row.querySelector('.status-val').value = btn.dataset.val;
    row.querySelectorAll('.att-btn').forEach(b => b.classList.remove('att-active'));
    btn.classList.add('att-active');
    row.dataset.status = btn.dataset.val;
    updateCounts();
}

function markAll(status) {
    document.querySelectorAll('.att-btn[data-val="' + status + '"]').forEach(btn => setStatus(btn));
}

function updateCounts() {
    const c = { present: 0, absent: 0, late: 0, half_day: 0, holiday: 0 };
    document.querySelectorAll('.att-row').forEach(r => {
        if (c[r.dataset.status] !== undefined) c[r.dataset.status]++;
    });
    document.getElementById('cP').textContent  = c.present;
    document.getElementById('cA').textContent  = c.absent;
    document.getElementById('cL').textContent  = c.late;
    document.getElementById('cH').textContent  = c.half_day;
    document.getElementById('cHo').textContent = c.holiday;
}

updateCounts();
</script>
@endpush
@endsection
