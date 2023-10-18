<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use HasFactory;

    public function parents()
    {
        return $this->hasMany(Parento::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}
