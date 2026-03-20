@extends('layouts.app')
@section('content')
<div class="page-header"><h4>Attendance Calendar</h4></div>
<div class="card mb-4">
    <div class="card-header">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="employee_id" class="form-select">
                    <option value="">Select Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ $employee_id == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="month" class="form-select">
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null,$m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="year" class="form-control" value="{{ $year }}" min="2020" max="2030">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">View</button>
            </div>
        </form>
    </div>
    @if($employee_id)
    <div class="card-body">
        @php
            $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;
            $colors = ['present'=>'success','absent'=>'danger','late'=>'warning','half_day'=>'info','holiday'=>'secondary'];
        @endphp
        <div class="row g-2">
            @for($d=1; $d<=$daysInMonth; $d++)
                @php
                    $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $att = $attendances[$dateStr] ?? null;
                    $dayName = \Carbon\Carbon::parse($dateStr)->format('D');
                    $isWeekend = in_array($dayName, ['Sat','Sun']);
                @endphp
                <div class="col-auto">
                    <div class="text-center p-2 rounded" style="width:60px;background:{{ $att ? '' : ($isWeekend ? '#f1f5f9' : '#fff') }};border:1px solid #e2e8f0">
                        <small class="text-muted d-block">{{ $dayName }}</small>
                        <strong>{{ $d }}</strong>
                        @if($att)
                            <div class="mt-1"><span class="badge bg-{{ $colors[$att->status] ?? 'secondary' }}" style="font-size:0.6rem">{{ strtoupper(substr($att->status,0,1)) }}</span></div>
                        @elseif($isWeekend)
                            <div class="mt-1"><span class="badge bg-light text-muted" style="font-size:0.6rem">OFF</span></div>
                        @else
                            <div class="mt-1"><span class="badge bg-light text-muted" style="font-size:0.6rem">-</span></div>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
        <div class="mt-3 d-flex gap-3 flex-wrap">
            <span><span class="badge bg-success">P</span> Present</span>
            <span><span class="badge bg-danger">A</span> Absent</span>
            <span><span class="badge bg-warning">L</span> Late</span>
            <span><span class="badge bg-info">H</span> Half Day</span>
            <span><span class="badge bg-secondary">H</span> Holiday</span>
        </div>
    </div>
    @else
    <div class="card-body text-center text-muted py-5">Select an employee to view attendance calendar</div>
    @endif
</div>
@endsection