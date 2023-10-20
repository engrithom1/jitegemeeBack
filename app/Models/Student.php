<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $fillable = ['student_status_id','hearth','admission','entry','school_from','relation_to','transfer_reason','parent_id','user_id','level_id','classroom_id','accademic_year','nationality','birth_date','behavior','first_name','middle_name','last_name','gender','phone','photo','home_address','email','index_no'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function parent()
    {
        return $this->belongsTo(Parento::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function student_status()
    {
        return $this->belongsTo(Student_status::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function exam_marks()
    {
        return $this->hasMany(ExamMarks::class);
    }

    public function host_exam_marks()
    {
        return $this->hasMany(HostExamMarks::class);
    }

    public function fee_payments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function duration_payments()
    {
        return $this->hasMany(DurationPayment::class);
    }

    public function deposit_slips()
    {
        return $this->hasMany(DepositSlip::class);
    }


}
