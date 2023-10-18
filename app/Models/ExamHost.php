<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamHost extends Model
{
    use HasFactory;

    protected $table = 'exam_hosts';
    protected $primaryKey = 'id';
    protected $fillable = ['level_id','exam_id','user_id','year','classroom_id'];
}
