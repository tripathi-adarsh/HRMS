<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Employee extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id','employee_id','name','email','phone','photo',
        'department_id','designation_id','salary','joining_date',
        'gender','dob','address','status'
    ];
    public function department() { return $this->belongsTo(Department::class); }
    public function designation() { return $this->belongsTo(Designation::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function leaves() { return $this->hasMany(Leave::class); }
    public function payrolls() { return $this->hasMany(Payroll::class); }
    public function performances() { return $this->hasMany(Performance::class); }
}