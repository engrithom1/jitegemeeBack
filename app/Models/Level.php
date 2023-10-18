<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
   
    protected $fillable = ['level'];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
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
