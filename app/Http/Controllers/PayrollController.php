<?php
namespace App\Http\Controllers;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
class PayrollController extends Controller {
    public function index(Request $request) {
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;
        $query = Payroll::with(['employee.department'])->where('month',$month)->where('year',$year);
        if ($request->search) $query->whereHas('employee', fn($q) => $q->where('name','like',"%{$request->search}%"));
        if ($request->status) $query->where('status', $request->status);
        $payrolls = $query->paginate(15);
        return view('payroll.index', compact('payrolls','month','year'));
    }
    public function generate(Request $request) {
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;
        $employees = Employee::where('status','active')->get();
        foreach ($employees as $emp) {
            Payroll::updateOrCreate(
                ['employee_id'=>$emp->id,'month'=>$month,'year'=>$year],
                ['basic_salary'=>$emp->salary,'bonus'=>0,'deduction'=>0,'net_salary'=>$emp->salary]
            );
        }
        return redirect()->route('payroll.index',['month'=>$month,'year'=>$year])->with('success','Payroll generated.');
    }
    public function edit(Payroll $payroll) {
        return view('payroll.edit', compact('payroll'));
    }
    public function update(Request $request, Payroll $payroll) {
        $request->validate(['bonus'=>'numeric','deduction'=>'numeric']);
        $net = $payroll->basic_salary + $request->bonus - $request->deduction;
        $payroll->update(['bonus'=>$request->bonus,'deduction'=>$request->deduction,'net_salary'=>$net,'status'=>$request->status]);
        return redirect()->route('payroll.index')->with('success','Payroll updated.');
    }
    public function payslip(Payroll $payroll) {
        $payroll->load('employee.department','employee.designation');
        return view('payroll.payslip', compact('payroll'));
    }

    public function calculator() {
        return view('payroll.calculator');
    }
}