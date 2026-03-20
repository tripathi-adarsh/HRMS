<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ESSController;

Route::get('/', fn() => redirect()->route('dashboard'));

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Departments - Admin & HR only
    Route::middleware(['role:admin|hr'])->group(function () {
        Route::resource('departments', DepartmentController::class);
        Route::resource('designations', DesignationController::class);
        Route::resource('employees', EmployeeController::class);
        Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
        Route::post('payroll/generate', [PayrollController::class, 'generate'])->name('payroll.generate');
        Route::get('payroll/{payroll}/edit', [PayrollController::class, 'edit'])->name('payroll.edit');
        Route::put('payroll/{payroll}', [PayrollController::class, 'update'])->name('payroll.update');
        Route::get('payroll/{payroll}/payslip', [PayrollController::class, 'payslip'])->name('payroll.payslip');
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::post('attendance/bulk', [AttendanceController::class, 'bulkStore'])->name('attendance.bulk');
        Route::get('attendance/calendar', [AttendanceController::class, 'calendar'])->name('attendance.calendar');
        Route::get('leaves', [LeaveController::class, 'index'])->name('leaves.index');
        Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
        Route::delete('leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
        Route::resource('performance', PerformanceController::class);
        Route::get('reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
        Route::get('reports/leave', [ReportController::class, 'leave'])->name('reports.leave');
        Route::get('reports/payroll', [ReportController::class, 'payroll'])->name('reports.payroll');
    });

    // Employee self-service
    Route::get('my-attendance', [AttendanceController::class, 'calendar'])->name('my.attendance');
    Route::post('attendance/punch-in', [AttendanceController::class, 'punchIn'])->name('attendance.punchIn');
    Route::post('attendance/punch-out', [AttendanceController::class, 'punchOut'])->name('attendance.punchOut');
    Route::get('my-leaves', [LeaveController::class, 'index'])->name('my.leaves');
    Route::get('leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('my-performance', [PerformanceController::class, 'index'])->name('my.performance');

    // ESS Portal
    Route::get('ess', [ESSController::class, 'portal'])->name('ess.portal');
    Route::post('ess/punch-in', [ESSController::class, 'punchIn'])->name('ess.punchIn');
    Route::post('ess/punch-out', [ESSController::class, 'punchOut'])->name('ess.punchOut');

    // Salary calculator — accessible by all roles
    Route::get('salary-calculator', [PayrollController::class, 'calculator'])->name('salary.calculator');
});