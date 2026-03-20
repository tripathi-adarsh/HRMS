<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Leave extends Model {
    use HasFactory;
    protected $fillable = ['employee_id','leave_type_id','from_date','to_date','days','reason','status','remarks'];
    public function employee() { return $this->belongsTo(Employee::class); }
    public function leaveType() { return $this->belongsTo(LeaveType::class); }
}