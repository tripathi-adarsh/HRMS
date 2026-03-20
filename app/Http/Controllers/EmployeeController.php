<?php
namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class EmployeeController extends Controller {
    public function index(Request $request) {
        $query = Employee::with('department','designation');
        if ($request->search) $query->where('name','like',"%{$request->search}%")->orWhere('employee_id','like',"%{$request->search}%");
        if ($request->department_id) $query->where('department_id',$request->department_id);
        if ($request->status) $query->where('status',$request->status);
        $employees = $query->paginate(10);
        $departments = Department::all();
        return view('employees.index', compact('employees','departments'));
    }
    public function create() {
        $departments = Department::all();
        $designations = Designation::all();
        return view('employees.create', compact('departments','designations'));
    }
    public function store(Request $request) {
        $request->validate([
            'name'=>'required','email'=>'required|email|unique:employees,email',
            'employee_id'=>'required|unique:employees,employee_id',
            'department_id'=>'required','designation_id'=>'required',
            'salary'=>'required|numeric',
        ]);
        $data = $request->except('photo','password');
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees','public');
        }
        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password ?? 'password123'),
            'role' => 'employee',
        ]);
        $data['user_id'] = $user->id;
        Employee::create($data);
        return redirect()->route('employees.index')->with('success','Employee added successfully.');
    }
    public function show(Employee $employee) {
        $employee->load('department','designation','attendances','leaves','payrolls','performances');
        return view('employees.show', compact('employee'));
    }
    public function edit(Employee $employee) {
        $departments = Department::all();
        $designations = Designation::all();
        return view('employees.edit', compact('employee','departments','designations'));
    }
    public function update(Request $request, Employee $employee) {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:employees,email,'.$employee->id,
            'salary'=>'required|numeric',
        ]);
        $data = $request->except('photo');
        if ($request->hasFile('photo')) {
            if ($employee->photo) Storage::disk('public')->delete($employee->photo);
            $data['photo'] = $request->file('photo')->store('employees','public');
        }
        $employee->update($data);
        return redirect()->route('employees.index')->with('success','Employee updated.');
    }
    public function destroy(Employee $employee) {
        if ($employee->photo) Storage::disk('public')->delete($employee->photo);
        if ($employee->user) $employee->user->delete();
        $employee->delete();
        return redirect()->route('employees.index')->with('success','Employee deleted.');
    }
}