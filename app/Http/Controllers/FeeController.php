<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\Fee;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return Fee::all();
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ///validatio goes here
       $validator = Validator::make($request->all(),[
        'fee' => ['required', 'string', 'max:255', 'unique:fees'],
        'amount' => 'required',
        'min_amount' => 'required',
        'duration' => 'required',
       ]);

       if($validator->fails()){
        $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
        return response()->json($response, 200);

    }else{

        $user_id = $request->user_id;
        $role_id = $request->role_id;
        $fee = $request->fee;
        $amount = $request->amount;
        $min_amount = $request->min_amount;
        $duration = $request->duration;
        
        if($role_id < 3){
            return response()->json(['success' => false,
            'message' => ['You not allowed to make this action']], 200); 
        }

        $feez = [
            'user_id' => $user_id,
            'fee' => $fee,
            'amount' => $amount,
            'min_amount' => $min_amount,
            'duration' => $duration,
        ];

        $log = [
            'user_id' => $user_id,
            'log' => 'Create the fee by the name of '.$fee
        ];

        Fee::create($feez);
        app('App\Http\Controllers\LogController')->storeLogs($log);

        $fees = Fee::all();

        $response = [
            'success' => true,
            'message' => "fee added Successfuly",
            'fees'   => $fees
        ];

        return response()->json($response, 200);
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
         ///validatio goes here
       $validator = Validator::make($request->all(),[
        'fee' => ['required', 'string', 'max:255'],
        'amount' => 'required',
        'min_amount' => 'required',
        'duration' => 'required',
       ]);

       if($validator->fails()){
        $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
        return response()->json($response, 200);

        }else{

            $user_id = $request->user_id;
            $role_id = $request->user_id;
            $fee = $request->fee;
            $amount = $request->amount;
            $min_amount = $request->min_amount;
            $duration = $request->duration;
            $fee_id = $request->fee_id;
            $og_fee = $request->og_fee;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $feez = [
                'user_id' => $user_id,
                'fee' => $fee,
                'amount' => $amount,
                'min_amount' => $min_amount,
                'duration' => $duration,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'Fee edited from the name of '.$og_fee.' to '.$fee
            ];

            Fee::where(['id'=>$fee_id])->update($feez);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $fees = Fee::all();

            $response = [
                'success' => true,
                'message' => "Fee edited Successfuly",
                'fees'   => $fees
            ];

            return response()->json($response, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
    * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {

            $user_id = $request->user_id;
            $role_id = $request->role_id;
            $fee_id = $request->fee_id;
            $fee = $request->fee;
        
            if($role_id < 4){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $log = [
                'user_id' => $user_id,
                'log' => 'Delete the Fee by the name of '.$fee.', its id was '.$fee_id,
            ];
    
            Fee::where(['id' => $fee_id])->delete();
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $fees = Fee::all();
    
            $response = [
                'success' => true,
                'message' => "fee deleted Successfuly",
                'fees'   => $fees
            ];
    
            return response()->json($response, 200);
        
        } catch (\Throwable $th) {
            //return $th;
            return response()->json(['success' => false,
                'message' => ['Database or Server Errors']], 200);
        }
    }
}
