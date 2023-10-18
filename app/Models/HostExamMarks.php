<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostExamMarks extends Model
{
    use HasFactory;

    protected $table = 'exam_marks';
    protected $primaryKey = 'id';
    protected $fillable = ['level_id','exam_id','user_id','year','classroom_id','student_id','subjects','total_marks','points','details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
