<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionAtendance extends Model
{
    use HasFactory;

    protected $table = 'session_atendances';
    protected $primaryKey = 'id';
    protected $fillable = ['level_id','student_id','subject_id','user_id','classroom_id','year','date_att','date_no','attend'];
}
