<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Level;
use App\Models\Fee;
use App\Models\Role;
use App\Models\Parento;
use App\Models\Student;
use App\Models\ExamMarks;
use App\Models\FeePayment;
use App\Models\DurationPayment;
use App\Models\FeeBalance;
use App\Models\DepositSlip;
use App\Models\RemoveFee;
use App\Models\Attendance;
use DB;

class DashbordController extends Controller
{
      /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function dashbordDatas(Request $request)
   {

    $year = $request->year;

    $students = Student::where('student_status_id',1)->count();
    $staffs = Staff::count();
    $balances = FeeBalance::sum('amount');
    $paids = FeePayment::where('year',$year)->sum('paid_amount');
    $required = FeePayment::where('year',$year)->sum('amount');
    $debits = $required - $paids;


    $subjects = Subject::count();
    $teachers = Staff::where('role_id',2)->count();
    
    ////attendance
    $att_p = Attendance::where(['attend' => 1, 'year' => $year])->count();
    $att_a = Attendance::where(['attend' => 0, 'year' => $year])->count();

    $att_total = $att_a + $att_p;

    $attendance = round(($att_p * 100)/$att_total,1);

    //return $balances;

    $response = [
        'success' => true,
        'message' => "Request for this Fee was aleady been sent, so wait for approve",
        'students' => $students,
        'staffs' => $staffs,
        'attendance' => $attendance,
        'subjects' => $subjects,
        'balances' => $balances,
        'teachers' => $teachers,
        'paids' => $paids,
        'debits' => $debits
    ];
    return response()->json($response, 200);
    
   }
}
