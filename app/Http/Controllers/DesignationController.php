<?php
namespace App\Http\Controllers;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;
class DesignationController extends Controller {
    public function index(Request $request) {
        $query = Designation::with('department');
        if ($request->search) $query->where('name','like',"%{$request->search}%");
        if ($request->department_id) $query->where('department_id',$request->department_id);
        $designations = $query->paginate(10);
        $departments = Department::all();
        return view('designations.index', compact('designations','departments'));
    }
    public function create() {
        $departments = Department::all();
        return view('designations.create', compact('departments'));
    }
    public function store(Request $request) {
        $request->validate(['name'=>'required','department_id'=>'required|exists:departments,id']);
        Designation::create($request->only('name','department_id'));
        return redirect()->route('designations.index')->with('success','Designation created.');
    }
    public function edit(Designation $designation) {
        $departments = Department::all();
        return view('designations.edit', compact('designation','departments'));
    }
    public function update(Request $request, Designation $designation) {
        $request->validate(['name'=>'required','department_id'=>'required|exists:departments,id']);
        $designation->update($request->only('name','department_id'));
        return redirect()->route('designations.index')->with('success','Designation updated.');
    }
    public function destroy(Designation $designation) {
        $designation->delete();
        return redirect()->route('designations.index')->with('success','Designation deleted.');
    }
}