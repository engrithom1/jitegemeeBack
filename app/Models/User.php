<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username','password','index_no','type','status','role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
        
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function parents()
    {
        return $this->hasMany(Parento::class);
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
        
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function logs()
    {
        return $this->hasMany(Logs::class);
    }
    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function exam_marks()
    {
        return $this->hasMany(ExamMarks::class);
    }

    public function host_exam_marks()
    {
        return $this->hasMany(HostExamMarks::class);
    }

    public function fee_balances()
    {
        return $this->hasMany(FeeBalance::class);
    }

    public function fee_payments()
    {
        return $this->hasMany(FeePayment::class);
    }

    public function duration_payments()
    {
        return $this->hasMany(DurationPayment::class);
    }

    public function deposit_slips()
    {
        return $this->hasMany(DepositSlip::class);
    }

   

}
