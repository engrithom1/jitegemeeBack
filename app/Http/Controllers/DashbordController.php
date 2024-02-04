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
    public function dashDebits(Request $request)
    {
 
     $year = $request->year;
     try {
      $paids = FeePayment::where('year',$year)->sum('paid_amount');
      $required = FeePayment::where('year',$year)->sum('amount');
       return $required - $paids;
     } catch (\Throwable $th) {
       return 0;
     }
     
  
     }
       /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function dashPaids(Request $request)
    {
 
     $year = $request->year;
     try {
      return FeePayment::where('year',$year)->sum('paid_amount');
     } catch (\Throwable $th) {
      return 0;
     }
     }
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashSubjects()
    {
      try {
        return Subject::count();
      } catch (\Throwable $th) {
        return 0;
      }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashStaffs()
    {
      try {
        return Staff::count();
      } catch (\Throwable $th) {
        return 0;
      }

    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashTeachers()
    {
      try {
        return Staff::where('role_id',2)->count();
      } catch (\Throwable $th) {
        return 0;
      }

    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashStudents()
    {
      try {
        return Student::where('student_status_id',1)->count();
      } catch (\Throwable $th) {
        return 0;
      }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashBalances()
    {
      try {
        return FeeBalance::sum('amount');
      } catch (\Throwable $th) {
        return 0;
      }

    }
      /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function dashAttendance(Request $request)
   {

    try {
      $year = $request->year;
    
      ////attendance
      $att_p = Attendance::where(['attend' => 1, 'year' => $year])->count();
      $att_a = Attendance::where(['attend' => 0, 'year' => $year])->count();

      $att_total = $att_a + $att_p;

      if($att_p == 0){
        return 0;
      }

      if($att_total == 0){
        return 0;
      }else{
        return round(($att_p * 100)/$att_total,1);
      }
    } catch (\Throwable $th) {
      return 0;
    }

}

}
