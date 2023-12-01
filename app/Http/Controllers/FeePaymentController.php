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
use App\Models\DurationPayment;
use App\Models\FeeBalance;
use App\Models\DepositSlip;
use App\Models\RemoveFee;
use DB;

class FeePaymentController extends Controller
{
     /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function removeFeeRequest(Request $request)
   {

       ///validatio goes here
       $validator = Validator::make($request->all(),[
        'fee_payment_id' => ['required', 'integer', 'max:255','unique:remove_fees'],
       ]);

       if($validator->fails()){
        $response = [
            'success' => false,
            'message' => "Request for this Fee was aleady been sent, so wait for approve"
        ];
        return response()->json($response, 200);

        }else{

            $amount = $request->amount;
            $paid_amount = $request->paid_amount;
            $fee_id = $request->fee_id;
            $fee_payment_id = $request->fee_payment_id;
            $fee_name = $request->fee_name;
            $role_id = $request->role_id;
            $student_id = $request->student_id;
            $user_id = $request->user_id;
            $year = $request->year;
            $reason = $request->reason;

            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'year' => $year,
                'fee_name' => $fee_name,
                'reason' => $reason,
                'action' => 'not yet',
                'actionable_id' => 0,
                'amount' => $amount,
                'paid_amount' => $paid_amount,
                'student_id' => $student_id,
                'user_id' => $user_id,
                'fee_id' => $fee_id,
                'fee_payment_id' => $fee_payment_id,
                'status' => 0,
            ];

            RemoveFee::create($data);

            return response()->json(['success' => true,
            'message' => ['Request sent successfully, Fee will be removed after being approved']], 200);
        }    
   }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function addPaymentToStudent(Request $request)
   {
        $class_id = $request->class_id;
        $year = $request->year;
        $level_id = $request->level_id;

       $pay_amount = $request->pay_amount;
       $valid_to = $request->valid_to;

       $b_amount = $request->b_amount;
       $b_id = $request->b_id;

       $s_paid_amount = $request->s_paid_amount;
       $s_id = $request->s_id;
       $fee_id = $request->f_id;

       $role_id = $request->role_id;
       $student_id = $request->student_id;
       $user_id = $request->user_id;
    

       if($role_id < 3){
           return response()->json(['success' => false,
           'message' => ['You not allowed to make this action']], 200); 
       }

       $balance = $b_amount - $pay_amount;

       $paid_amount = $s_paid_amount + $pay_amount;

       $row_arr = [
            'amount' => $pay_amount,
            'student_id' => $student_id,
            'level_id' => $level_id,
            'user_id'  => $user_id,
            'year' => $year,
            'fee_payment_id' => $s_id,
            'classroom_id' => $class_id,
            'fee_id' => $fee_id
       ];

       DurationPayment::create($row_arr);
       FeeBalance::where(['student_id' => $student_id])->update(['amount' => $balance]);
       FeePayment::where(['student_id' => $student_id,'level_id' => $level_id,'year' => $year,'fee_id'=>$fee_id])->update(['paid_amount' => $paid_amount,'valid_to' => $valid_to,'status'=>1]);

       $student_balance = FeeBalance::where(['student_id' => $student_id])->get();

       $feepay = DB::table('fee_payments')
                    ->join('fees','fees.id','=','fee_payments.fee_id')
                    ->select('fees.id AS f_id','fees.fee','fee_payments.id','fee_payments.amount','fee_payments.paid_amount','fee_payments.status')
                    ->where(['year' => $year, 'fee_payments.level_id' => $level_id, 'student_id' => $student_id])->get();

        $response = [
            'success' => true,
            'message' => "Succesfully Paid..",
            'feepay'  => $feepay,
            'student_balance' =>  $student_balance[0]
        ];
        
        return response()->json($response, 200);
    }   
        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addFeeToStudent(Request $request)
    {
        $amount = $request->fee_amount;
        $class_id = $request->class_id;
        $fee_id = $request->fee_id;
        $level_id = $request->level_id;
        $role_id = $request->role_id;
        $student_id = $request->student_id;
        $user_id = $request->user_id;
        $year = $request->year;

        if($role_id < 3){
            return response()->json(['success' => false,
            'message' => ['You not allowed to make this action']], 200); 
        }

        $row_arr = [
            'valid_to' => $year,
            'amount' => $amount,
            'paid_amount' => 0,
            'student_id' => $student_id,
            'level_id' => $level_id,
            'user_id'  => $user_id,
            'year' => $year,
            'status' => 0,
            'classroom_id' => $class_id,
            'fee_id' => $fee_id
        ];

        $feez = FeePayment::where(['year' => $year, 'level_id' => $level_id, 'student_id' => $student_id,'fee_id' => $fee_id])->get();
        
        if(count($feez) === 0){
            FeePayment::create($row_arr);
        }

        $feepay = DB::table('fee_payments')
                    ->join('fees','fees.id','=','fee_payments.fee_id')
                    ->select('fees.id AS f_id','fees.fee','fee_payments.id','fee_payments.amount','fee_payments.paid_amount','fee_payments.status')
                    ->where(['year' => $year, 'fee_payments.level_id' => $level_id, 'student_id' => $student_id])->get();

        $response = [
            'success' => true,
            'message' => "Fee added to Student Successfuly",
            'feepay'  => $feepay,
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
                    ->select('fees.id AS f_id','fees.fee','fee_payments.id','fee_payments.amount','fee_payments.paid_amount','fee_payments.status')
                    ->where(['year' => $year, 'fee_payments.level_id' => $level_id, 'student_id' => $student_id])->get();

        if($level_id < 5){
            $o_fees = Fee::where(['level_id' => 0])
                    ->orWhere(['level_id' => 7])
                    ->orWhere(['level_id' => $level_id])
                    ->get();
        }

        if($level_id > 4){
            $o_fees = Fee::where(['level_id' => 0])
                    ->orWhere(['level_id' => 8])
                    ->orWhere(['level_id' => $level_id])
                    ->get();
        }

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
                    ->select('fees.id AS f_id','fees.fee','fee_payments.id','fee_payments.amount','fee_payments.paid_amount','fee_payments.status')
                    ->where(['year' => $year, 'fee_payments.level_id' => $level_id, 'student_id' => $student_id])->get();

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
