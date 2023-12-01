<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'id';
    protected $fillable = ['role_id','user_id','about_me','department_id','initial','first_name','middle_name','last_name','gender','phone','photo','home_address','email','index_no'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
