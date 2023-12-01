<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $table = 'fees';
    protected $primaryKey = 'id';
    protected $fillable = ['fee','user_id','level_id','status','amount','duration','min_amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
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
