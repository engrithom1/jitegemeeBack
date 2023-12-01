<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $fillable = ['department','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

}
