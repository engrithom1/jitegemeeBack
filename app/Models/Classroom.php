<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classrooms';
    protected $primaryKey = 'id';
    protected $fillable = ['classname','roomnumber','fees','subjects','students','user_id','level_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function exammarks()
    {
        return $this->hasMany(ExamMarks::class);
    }

    public function fee_payments()
    {
        return $this->hasMany(FeePayment::class);
    }
    
    public function duration_payments()
    {
        return $this->hasMany(DurationPayment::class);
    }

}
