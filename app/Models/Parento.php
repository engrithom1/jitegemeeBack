<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parento extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $primaryKey = 'id';
    protected $fillable = ['nationality','user_id','occupation','first_name','middle_name','last_name','gender','phone','photo','home_address','email','index_no'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
