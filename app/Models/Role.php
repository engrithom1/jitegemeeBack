<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = ['role','color'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}
