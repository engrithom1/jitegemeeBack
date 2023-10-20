<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositSlip extends Model
{
    use HasFactory;

    protected $table = 'deposit_slips';
    protected $primaryKey = 'id';
    protected $fillable = ['student_id','user_id','year','amount','transation_no','description','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
