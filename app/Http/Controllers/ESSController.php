<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ESSController extends Controller
{
    private function getEmployee()
    {
        return auth()->user()->employee;
    }

    public function portal()
    {
        $emp = $this->getEmployee();
        if (!$emp) return redirect()->route('dashboard')->with('error', 'No employee profile linked to your account.');

        $now   = Carbon::now();
        $month = $now->month;
        $year  = $now->year;

        // Today's attendance
        $todayAtt = Attendance::where('employee_id', $emp->id)
            ->where('date', $now->toDateString())->first();

        // This month attendance summary
        $monthAtts = Attendance::where('employee_id', $emp->id)
            ->whereMonth('date', $month)->whereYear('date', $year)->get();

        $attSummary = [
            'present'  => $monthAtts->where('status','present')->count(),
            'absent'   => $monthAtts->where('status','absent')->count(),
            'late'     => $monthAtts->where('status','late')->count(),
            'half_day' => $monthAtts->where('status','half_day')->count(),
            'holiday'  => $monthAtts->where('status','holiday')->count(),
            'total'    => $monthAtts->count(),
        ];

        // Current month payroll
        $payroll = Payroll::where('employee_id', $emp->id)
            ->where('month', $month)->where('year', $year)->first();

        // Last 3 months payroll
        $recentPayrolls = Payroll::where('employee_id', $emp->id)
            ->orderByDesc('year')->orderByDesc('month')->take(6)->get();

        // Leave summary this year
        $leaves = Leave::where('employee_id', $emp->id)
            ->whereYear('from_date', $year)
            ->with('leaveType')->latest()->get();

        $leaveSummary = [
            'approved' => $leaves->where('status','approved')->sum('days'),
            'pending'  => $leaves->where('status','pending')->count(),
            'rejected' => $leaves->where('status','rejected')->count(),
            'total'    => $leaves->count(),
        ];

        $leaveTypes = LeaveType::all();

        // Working days this month (Mon–Fri)
        $workingDays = 0;
        $daysInMonth = $now->daysInMonth;
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $day = Carbon::create($year, $month, $d);
            if (!$day->isWeekend()) $workingDays++;
        }

        // Estimated salary (pro-rated if attendance exists)
        $workedDays = $attSummary['present'] + $attSummary['late'] + ($attSummary['half_day'] * 0.5);
        $estimatedSalary = $workingDays > 0
            ? round(($emp->salary / $workingDays) * $workedDays)
            : $emp->salary;

        return view('ess.portal', compact(
            'emp','todayAtt','attSummary','payroll','recentPayrolls',
            'leaves','leaveSummary','leaveTypes','workingDays',
            'workedDays','estimatedSalary','month','year'
        ));
    }

    public function punchIn()
    {
        $emp = $this->getEmployee();
        $existing = Attendance::where('employee_id', $emp->id)
            ->where('date', Carbon::today())->first();

        if ($existing && $existing->punch_in) {
            return back()->with('error', 'Already punched in at ' . Carbon::parse($existing->punch_in)->format('h:i A'));
        }

        Attendance::updateOrCreate(
            ['employee_id' => $emp->id, 'date' => Carbon::today()],
            ['status' => 'present', 'punch_in' => Carbon::now()->format('H:i:s')]
        );

        return back()->with('success', 'Punched in at ' . Carbon::now()->format('h:i A'));
    }

    public function punchOut()
    {
        $emp = $this->getEmployee();
        $att = Attendance::where('employee_id', $emp->id)
            ->where('date', Carbon::today())->first();

        if (!$att || !$att->punch_in) {
            return back()->with('error', 'You have not punched in today.');
        }
        if ($att->punch_out) {
            return back()->with('error', 'Already punched out at ' . Carbon::parse($att->punch_out)->format('h:i A'));
        }

        $att->update(['punch_out' => Carbon::now()->format('H:i:s')]);

        $hours = Carbon::parse($att->punch_in)->diffInMinutes(Carbon::now()) / 60;
        return back()->with('success', 'Punched out at ' . Carbon::now()->format('h:i A') . ' — ' . round($hours, 1) . ' hrs worked');
    }
}
