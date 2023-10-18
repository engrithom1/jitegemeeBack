<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $fillable = ['subject','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exammarks()
    {
        return $this->hasMany(ExamMarks::class);
    }
   
}
