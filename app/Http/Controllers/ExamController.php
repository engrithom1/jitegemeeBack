<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Models\Exam;
//use App\Models\Exam;
use App\Models\User;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return Exam::all();
        } catch (\Throwable $th) {
            //throw $th;
            return $th;
        }
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeExams()
    {
        try {
            return Exam::where(['status'=>'active'])->get();
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
        'examname' => ['required', 'string', 'max:255', 'unique:exams'],
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
        $examname = $request->examname;
        
        if($role_id < 3){
            return response()->json(['success' => false,
            'message' => ['You not allowed to make this action']], 200); 
        }

        $exam = [
            'user_id' => $user_id,
            'examname' => $examname,
        ];

        $log = [
            'user_id' => $user_id,
            'log' => 'Create the Exam by the name of '.$examname
        ];

        Exam::create($exam);
        app('App\Http\Controllers\LogController')->storeLogs($log);

        $exams = Exam::all();

        $response = [
            'success' => true,
            'message' => "Exam added Successfuly",
            'exams'   => $exams
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
        'examname' => ['required', 'string', 'max:255', 'unique:exams'],
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
            $examname = $request->examname;
            $exam_id = $request->exam_id;
            $og_examname = $request->og_examname;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $exam = [
                'user_id' => $user_id,
                'examname' => $examname,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'Exam edited from the name of '.$og_examname.' to '.$examname
            ];

            Exam::where(['id'=>$exam_id])->update($exam);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $exams = Exam::all();

            $response = [
                'success' => true,
                'message' => "Exam edited Successfuly",
                'exams'   => $exams
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
            $exam_id = $request->exam_id;
            $exam = $request->exam;
        
            if($role_id < 4){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $log = [
                'user_id' => $user_id,
                'log' => 'Delete the Exam by the name of '.$exam.', its id was '.$exam_id,
            ];
    
            Exam::where(['id' => $exam_id])->delete();
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $exams = Exam::all();
    
            $response = [
                'success' => true,
                'message' => "Exam deleted Successfuly",
                'exams'   => $exams
            ];
    
            return response()->json($response, 200);
        
        } catch (\Throwable $th) {
            //return $th;
            return response()->json(['success' => false,
                'message' => ['Database or Server Errors']], 200);
        }
    }
}
