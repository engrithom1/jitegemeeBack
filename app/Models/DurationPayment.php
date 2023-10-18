<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DurationPayment extends Model
{
    use HasFactory;

    protected $table = 'duration_payments';
    protected $primaryKey = 'id';
    protected $fillable = ['level_id','student_id','fee_payment_id','fee_id','user_id','classroom_id','year','amount'];

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

    public function fee_payments()
    {
        return $this->belongsTo(FeePayment::class);
    }
}
