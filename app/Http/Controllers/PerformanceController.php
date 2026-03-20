<?php
namespace App\Http\Controllers;
use App\Models\Performance;
use App\Models\Employee;
use Illuminate\Http\Request;
class PerformanceController extends Controller {
    public function index(Request $request) {
        $query = Performance::with(['employee.department']);
        if (auth()->user()->isEmployee()) {
            $query->where('employee_id', auth()->user()->employee->id ?? 0);
        }
        if ($request->search) $query->where(function($q) use ($request) {
            $q->whereHas('employee', fn($eq) => $eq->where('name','like',"%{$request->search}%"))
              ->orWhere('task','like',"%{$request->search}%");
        });
        if ($request->status) $query->where('status', $request->status);
        if ($request->rating) $query->where('rating', $request->rating);
        $performances = $query->latest()->paginate(15);
        return view('performance.index', compact('performances'));
    }
    public function create() {
        $employees = Employee::where('status','active')->get();
        return view('performance.create', compact('employees'));
    }
    public function store(Request $request) {
        $request->validate(['employee_id'=>'required','task'=>'required']);
        Performance::create($request->only('employee_id','task','description','status'));
        return redirect()->route('performance.index')->with('success','Task assigned.');
    }
    public function edit(Performance $performance) {
        $employees = Employee::where('status','active')->get();
        return view('performance.edit', compact('performance','employees'));
    }
    public function update(Request $request, Performance $performance) {
        $request->validate(['task'=>'required']);
        $performance->update($request->only('task','description','rating','review','review_date','status'));
        return redirect()->route('performance.index')->with('success','Performance updated.');
    }
    public function destroy(Performance $performance) {
        $performance->delete();
        return back()->with('success','Record deleted.');
    }
}