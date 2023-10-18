<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';
    protected $primaryKey = 'id';
    protected $fillable = ['examname','user_id','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exammarks()
    {
        return $this->hasMany(ExamMarks::class);
    }
   

}
