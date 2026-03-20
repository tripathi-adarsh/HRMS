<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AttendanceController extends Controller {
    public function index(Request $request) {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');

        $empQuery = Employee::where('status','active')->with('department');
        if ($request->department_id) $empQuery->where('department_id', $request->department_id);
        if ($request->search)        $empQuery->where('name','like',"%{$request->search}%");
        $employees = $empQuery->orderBy('name')->get();

        $attendances = Attendance::where('date',$date)
            ->whereIn('employee_id', $employees->pluck('id'))
            ->get();

        $departments = \App\Models\Department::orderBy('name')->get();
        return view('attendance.index', compact('attendances','date','employees','departments'));
    }
    public function calendar(Request $request) {
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;
        $employee_id = $request->employee_id;
        $attendances = Attendance::where('employee_id',$employee_id)
            ->whereMonth('date',$month)->whereYear('date',$year)->get()->keyBy('date');
        $employees = Employee::where('status','active')->get();
        return view('attendance.calendar', compact('attendances','month','year','employees','employee_id'));
    }
    public function store(Request $request) {
        $request->validate(['employee_id'=>'required','date'=>'required|date','status'=>'required']);
        Attendance::updateOrCreate(
            ['employee_id'=>$request->employee_id,'date'=>$request->date],
            $request->only('status','punch_in','punch_out','note')
        );
        return back()->with('success','Attendance saved.');
    }
    public function bulkStore(Request $request) {
        $date = $request->date ?? Carbon::today()->format('Y-m-d');
        $employees = Employee::where('status','active')->get();
        foreach ($employees as $emp) {
            $status   = $request->input("status.{$emp->id}", 'absent');
            $punchIn  = $request->input("punch_in.{$emp->id}") ?: null;
            $punchOut = $request->input("punch_out.{$emp->id}") ?: null;
            Attendance::updateOrCreate(
                ['employee_id'=>$emp->id,'date'=>$date],
                ['status'=>$status,'punch_in'=>$punchIn,'punch_out'=>$punchOut]
            );
        }
        return back()->with('success','Attendance saved for '.Carbon::parse($date)->format('d M Y').'.');
    }
    public function punchIn(Request $request) {
        $emp = auth()->user()->employee;
        Attendance::updateOrCreate(
            ['employee_id'=>$emp->id,'date'=>Carbon::today()],
            ['status'=>'present','punch_in'=>Carbon::now()->format('H:i:s')]
        );
        return back()->with('success','Punched in at '.Carbon::now()->format('h:i A'));
    }
    public function punchOut(Request $request) {
        $emp = auth()->user()->employee;
        $att = Attendance::where('employee_id',$emp->id)->where('date',Carbon::today())->first();
        if ($att) $att->update(['punch_out'=>Carbon::now()->format('H:i:s')]);
        return back()->with('success','Punched out at '.Carbon::now()->format('h:i A'));
    }
}