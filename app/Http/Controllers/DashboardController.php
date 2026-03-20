<?php
namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Department;
use Carbon\Carbon;
class DashboardController extends Controller {
    public function index() {
        $user = auth()->user();

        // Employee gets their own dashboard
        if ($user->isEmployee()) {
            $emp = $user->employee;
            if (!$emp) {
                return view('dashboard', ['isEmployee' => true, 'emp' => null,
                    'totalEmployees'=>0,'todayPresent'=>0,'onLeave'=>0,'monthlySalary'=>0,
                    'departments'=>collect(),'recentLeaves'=>collect(),'attendanceStats'=>collect(),
                    'pendingLeaves'=>0,'absentToday'=>0,'newJoinees'=>0]);
            }
            return redirect()->route('ess.portal');
        }

        $totalEmployees = Employee::where('status','active')->count();
        $todayPresent = Attendance::where('date', Carbon::today())->where('status','present')->count();
        $onLeave = Leave::where('status','approved')
            ->where('from_date','<=', Carbon::today())
            ->where('to_date','>=', Carbon::today())->count();
        $monthlySalary = Payroll::where('month', Carbon::now()->month)
            ->where('year', Carbon::now()->year)->sum('net_salary');
        $departments = Department::withCount('employees')->get();
        $recentLeaves = Leave::with(['employee.department','leaveType'])->latest()->take(8)->get();
        $attendanceStats = Attendance::where('date', Carbon::today())
            ->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count','status');
        $pendingLeaves = Leave::where('status','pending')->count();
        $absentToday = Attendance::where('date', Carbon::today())->where('status','absent')->count();
        $newJoinees = \App\Models\Employee::whereMonth('joining_date', Carbon::now()->month)
            ->whereYear('joining_date', Carbon::now()->year)->count();
        return view('dashboard', compact(
            'totalEmployees','todayPresent','onLeave','monthlySalary',
            'departments','recentLeaves','attendanceStats',
            'pendingLeaves','absentToday','newJoinees'
        ));
    }
}