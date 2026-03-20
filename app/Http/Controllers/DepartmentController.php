<?php
namespace App\Http\Controllers;
use App\Models\Department;
use Illuminate\Http\Request;
class DepartmentController extends Controller {
    public function index(Request $request) {
        $query = Department::withCount('employees');
        if ($request->search) $query->where('name','like',"%{$request->search}%");
        $departments = $query->paginate(10);
        return view('departments.index', compact('departments'));
    }
    public function create() { return view('departments.create'); }
    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:departments,name']);
        Department::create($request->only('name','description','is_active'));
        return redirect()->route('departments.index')->with('success','Department created successfully.');
    }
    public function edit(Department $department) { return view('departments.edit', compact('department')); }
    public function update(Request $request, Department $department) {
        $request->validate(['name' => 'required|unique:departments,name,'.$department->id]);
        $department->update($request->only('name','description','is_active'));
        return redirect()->route('departments.index')->with('success','Department updated successfully.');
    }
    public function destroy(Department $department) {
        $department->delete();
        return redirect()->route('departments.index')->with('success','Department deleted.');
    }
}