<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\Staff;
use App\Models\User;
use App\Models\Classroom;
use App\Models\student_status;
use App\Models\Level;
use App\Models\Fee;
use App\Models\Role;
use App\Models\Parento;
use App\Models\Student;
use App\Models\ExamMarks;
use App\Models\FeePayment;
use App\Models\FeeBalance;
use App\Models\DepositSlip;
use DB;

class FeePaymentController extends Controller
{
      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStudentByIndexNo(Request $request)
    {
        $index_no = $request->index_no;

        $student = DB::table('students')
                          ->join('student_statuses','student_statuses.id','=','studentS.student_status_id')
                          ->select('students.id','students.admission','students.index_no','students.first_name','students.middle_name','students.last_name','students.classroom_id','students.level_id','students.accademic_year','student_statuses.status_name')
                          ->where(['students.index_no' => $index_no])
                          ->first();

        $response = [
            'success' => true,
            'message' => "Class added Successfuly",
            'student'  => $student,
        ];

        return response()->json($response, 200);
    } 
    //checkRequiredFees   
       /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkRequiredFees(Request $request)
    {
        $admission_id = $request->admission_id;
        $class_id = $request->class_id;
        $fees = $request->fees;
        $level_id = $request->level_id;
        $role_id = $request->role_id;
        $student_id = $request->student_id;
        $user_id = $request->user_id;
        $year = $request->year;

        if($role_id < 3){
            return response()->json(['success' => false,
            'message' => ['You not allowed to make this action']], 200); 
        }

        Student::where(['id'=>$student_id])->update(['classroom_id' => $class_id, 'level_id' => $level_id,'admission' => $admission_id]);
        $fz = explode(',',$fees);
        $feepay = DB::table('fee_payments')
                    ->join('fees','fees.id','=','fee_payments.fee_id')
                    ->select('fees.fee','fee_payments.id','fee_payments.amount','fee_payments.paid_amount','fee_payments.status')
                    ->where(['year' => $year, 'level_id' => $level_id, 'student_id' => $student_id])->get();
        $o_fees = Fee::whereNotIn('id',$fz)->get();

        ////take care of balance man
        $student_balance = FeeBalance::where(['student_id' => $student_id])->get();
        if(count($student_balance) === 0){
            FeeBalance::create(['amount' => 0,'student_id'=>$student_id,'user_id'=>$user_id]);
        }
        $student_balance = FeeBalance::where(['student_id' => $student_id])->get();
        if(count($feepay) === 0){
            ///action where payments hazipo
            ///select fees on class
           
            $fzs = Fee::whereIn('id',$fz)->get();
            $stdz = [];

            foreach($fzs as $fe){
                $row_arr = [];

                $row_arr = [
                    'valid_to' => $year,
                    'amount' => $fe->amount,
                    'paid_amount' => 0,
                    'student_id' => $student_id,
                    'level_id' => $level_id,
                    'user_id'  => $user_id,
                    'year' => $year,
                    'status' => 0,
                    'classroom_id' => $class_id,
                    'fee_id' => $fe->id
                ];
                
    
                array_push($stdz, $row_arr);

            }
            
                DB::table('fee_payments')->insert($stdz);

                $feepay = DB::table('fee_payments')
                    ->join('fees','fees.id','=','fee_payments.fee_id')
                    ->select('fees.fee','fee_payments.id','fee_payments.amount','fee_payments.paid_amount','fee_payments.status')
                    ->where(['year' => $year, 'level_id' => $level_id, 'student_id' => $student_id])->get();

                $response = [
                    'success' => true,
                    'message' => "Class added Successfuly",
                    'feepay'  => $feepay,
                    'o_fees' =>  $o_fees,
                    'student_balance' =>  $student_balance[0]
                ];
        
                return response()->json($response, 200);
                
            
        }else{
            ///action where payments zipooo
            FeePayment::where(['student_id'=>$student_id, 'level_id' => $level_id, 'year' => $year])->update(['classroom_id' => $class_id]);

            $response = [
                'success' => true,
                'message' => "Class added Successfuly",
                'feepay'  => $feepay,
                'o_fees' =>  $o_fees,
                'student_balance' =>  $student_balance[0]
            ];
    
            return response()->json($response, 200);

        }

    } 
}
