<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeBalance extends Model
{
    use HasFactory;


    protected $table = 'fee_balances';
    protected $primaryKey = 'id';
    protected $fillable = ['student_id','user_id','amount','reason'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
