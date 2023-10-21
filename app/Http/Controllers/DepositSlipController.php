<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use App\Models\FeeBalance;
use App\Models\DepositSlip;
use DB;

class DepositSlipController extends Controller
{
            /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addDepositSlip(Request $request)
    {
          ///validatio goes here
       $validator = Validator::make($request->all(),[
        'deposit_code' => ['required', 'string', 'max:255','unique:deposit_slips'],
       ]);

       if($validator->fails()){
        $response = [
            'success' => false,
            'message' => "Deposite Code has aleady been used"
        ];
        return response()->json($response, 200);

        }else{
        $amount = $request->amount;
        $class_id = $request->class_id;
        $deposit_code = $request->deposit_code;
        $description = $request->description;
        $level_id = $request->level_id;
        $role_id = $request->role_id;
        $student_id = $request->student_id;
        $user_id = $request->user_id;
        $year = $request->year;
        $balance = $request->balance;

        $balance = $balance + $amount;

        if($role_id < 3){
            return response()->json(['success' => false,
            'message' => ['You not allowed to make this action']], 200); 
        }

        $row_arr = [
            'amount' => $amount,
            'student_id' => $student_id,
            'user_id'  => $user_id,
            'year' => $year,
            'description' => $description,
            'deposit_code' => $deposit_code,
            'status' => 0
       ];

       DepositSlip::create($row_arr);
       FeeBalance::where(['student_id' => $student_id])->update(['amount' => $balance]);

       $student_balance = FeeBalance::where(['student_id' => $student_id])->get();

       $response = [
        'success' => true,
        'message' => "Class added Successfuly",
        'student_balance' =>  $student_balance[0]
    ];
    
    return response()->json($response, 200);

    }
    
}
}
