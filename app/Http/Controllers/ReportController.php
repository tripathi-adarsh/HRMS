<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller {

    public function attendance(Request $request) {
        $month = $request->month ?? Carbon::now()->month;
        $year  = $request->year  ?? Carbon::now()->year;

        $query = Employee::where('status','active')
            ->with(['department','attendances' => fn($q) => $q->whereMonth('date',$month)->whereYear('date',$year)]);

        if ($request->department_id) $query->where('department_id', $request->department_id);
        if ($request->employee_id)   $query->where('id', $request->employee_id);

        $employees   = $query->get();
        $departments = Department::orderBy('name')->get();
        $allEmployees = Employee::where('status','active')->orderBy('name')->get();

        // Summary stats
        $totalPresent = 0; $totalAbsent = 0; $totalLate = 0; $totalHalf = 0;
        foreach ($employees as $emp) {
            $totalPresent += $emp->attendances->where('status','present')->count();
            $totalAbsent  += $emp->attendances->where('status','absent')->count();
            $totalLate    += $emp->attendances->where('status','late')->count();
            $totalHalf    += $emp->attendances->where('status','half_day')->count();
        }

        return view('reports.attendance', compact(
            'employees','month','year','departments','allEmployees',
            'totalPresent','totalAbsent','totalLate','totalHalf'
        ));
    }

    public function leave(Request $request) {
        $year = $request->year ?? Carbon::now()->year;

        $query = Leave::with(['employee.department','leaveType'])->whereYear('from_date',$year);

        if ($request->department_id)  $query->whereHas('employee', fn($q) => $q->where('department_id', $request->department_id));
        if ($request->employee_id)    $query->where('employee_id', $request->employee_id);
        if ($request->leave_type_id)  $query->where('leave_type_id', $request->leave_type_id);
        if ($request->status)         $query->where('status', $request->status);

        $leaves      = $query->latest()->get();
        $departments = Department::orderBy('name')->get();
        $allEmployees = Employee::where('status','active')->orderBy('name')->get();
        $leaveTypes  = \App\Models\LeaveType::orderBy('name')->get();

        // Summary
        $totalDays     = $leaves->sum('days');
        $approvedDays  = $leaves->where('status','approved')->sum('days');
        $pendingCount  = $leaves->where('status','pending')->count();
        $rejectedCount = $leaves->where('status','rejected')->count();

        return view('reports.leave', compact(
            'leaves','year','departments','allEmployees','leaveTypes',
            'totalDays','approvedDays','pendingCount','rejectedCount'
        ));
    }

    public function payroll(Request $request) {
        $year  = $request->year  ?? Carbon::now()->year;
        $month = $request->month ?? null;

        $query = Payroll::with(['employee.department'])->whereYear('created_at',$year);

        if ($month)                   $query->where('month', $month);
        if ($request->department_id)  $query->whereHas('employee', fn($q) => $q->where('department_id', $request->department_id));
        if ($request->employee_id)    $query->where('employee_id', $request->employee_id);
        if ($request->status)         $query->where('status', $request->status);

        $payrolls    = $query->orderBy('month')->get();
        $departments = Department::orderBy('name')->get();
        $allEmployees = Employee::where('status','active')->orderBy('name')->get();

        // Summary
        $totalNet    = $payrolls->sum('net_salary');
        $totalBonus  = $payrolls->sum('bonus');
        $totalDeduc  = $payrolls->sum('deduction');
        $paidCount   = $payrolls->where('status','paid')->count();

        return view('reports.payroll', compact(
            'payrolls','year','month','departments','allEmployees',
            'totalNet','totalBonus','totalDeduc','paidCount'
        ));
    }
}
