<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemoveFee extends Model
{
    use HasFactory;

    protected $table = 'remove_fees';
    protected $primaryKey = 'id';
    protected $fillable = ['fee_name','student_id','fee_id','fee_payment_id','user_id','year','amount','paid_amount','reason','actionable_id','action','status'];

}
