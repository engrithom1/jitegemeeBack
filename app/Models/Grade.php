<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';
    protected $primaryKey = 'id';
    protected $fillable = ['level_id','user_id','mark1','mark2','point','grade','grade_label'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function exammarks()
    {
        return $this->hasMany(ExamMarks::class);
    }
}
