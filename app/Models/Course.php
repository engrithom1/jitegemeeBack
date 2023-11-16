<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $fillable = ['coursename','user_id','subjects','subject_names'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
