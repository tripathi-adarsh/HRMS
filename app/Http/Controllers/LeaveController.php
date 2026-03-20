<?php
namespace App\Http\Controllers;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
class LeaveController extends Controller {
    public function index(Request $request) {
        $query = Leave::with(['employee.department','leaveType']);
        if (auth()->user()->isEmployee()) {
            $query->where('employee_id', auth()->user()->employee->id ?? 0);
        }
        if ($request->status) $query->where('status',$request->status);
        if ($request->search) $query->whereHas('employee', fn($q) => $q->where('name','like',"%{$request->search}%"));
        if ($request->leave_type_id) $query->where('leave_type_id', $request->leave_type_id);
        $leaves = $query->latest()->paginate(15);
        return view('leaves.index', compact('leaves'));
    }
    public function create() {
        $leaveTypes = LeaveType::all();
        $employee = auth()->user()->employee;
        return view('leaves.create', compact('leaveTypes','employee'));
    }
    public function store(Request $request) {
        $request->validate([
            'leave_type_id'=>'required','from_date'=>'required|date',
            'to_date'=>'required|date|after_or_equal:from_date','reason'=>'required',
        ]);
        $days = Carbon::parse($request->from_date)->diffInDays(Carbon::parse($request->to_date)) + 1;
        $emp = auth()->user()->isEmployee() ? auth()->user()->employee : Employee::find($request->employee_id);
        Leave::create([
            'employee_id' => $emp->id,
            'leave_type_id' => $request->leave_type_id,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'days' => $days,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);
        return redirect()->route('leaves.index')->with('success','Leave applied successfully.');
    }
    public function approve(Leave $leave) {
        $leave->update(['status'=>'approved']);
        return back()->with('success','Leave approved.');
    }
    public function reject(Request $request, Leave $leave) {
        $leave->update(['status'=>'rejected','remarks'=>$request->remarks]);
        return back()->with('success','Leave rejected.');
    }
    public function destroy(Leave $leave) {
        $leave->delete();
        return back()->with('success','Leave deleted.');
    }
}