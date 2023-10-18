<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;

    protected $table = 'fee_payments';
    protected $primaryKey = 'id';
    protected $fillable = ['level_id','student_id','fee_id','user_id','classroom_id','year','amount','paid_amount','valid_to','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classrooms()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function fees()
    {
        return $this->belongsTo(Fee::class);
    }

    public function duration_payments()
    {
        return $this->hasMany(DurationPayment::class);
    }

}
