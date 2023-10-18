<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use App\Models\Level;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return Grade::orderBy('grades.level_id','asc')
            ->orderBy('grades.mark1','desc')
            ->join('levels','grades.level_id', '=', 'levels.id')
            ->select('grades.id','grades.mark2','grades.mark1','grades.grade','grades.grade_label','grades.point','levels.level')
            ->get();
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
        'level_id' => 'required',
        'grade'  => 'required',
        'grade_label'  => 'required',
        'mark1'  => 'required',
        'mark2'  => 'required',
        'point'  => 'required',
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
            $level_id = $request->level_id;
            $grade = $request->grade;
            $mark1 = $request->mark1;
            $mark2 = $request->mark2;
            $point = $request->point;
            $grade_label = $request->grade_label;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'level_id' => $level_id,
                'user_id' => $user_id,
                'grade' => $grade,
                'grade_label' => $grade_label,
                'mark1' => $mark1,
                'mark2' => $mark2,
                'point' => $point, 
            ];

            //return $data;

            $log = [
                'user_id' => $user_id,
                'log' => 'Create the grade by the name of '.$grade
            ];

            Grade::create($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $grades = Grade::orderBy('grades.level_id','asc')
            ->orderBy('grades.mark1','desc')
            ->join('levels','grades.level_id', '=', 'levels.id')
            ->select('grades.id','grades.mark2','grades.mark1','grades.grade','grades.grade_label','grades.point','levels.level')
            ->get();

            $response = [
                'success' => true,
                'message' => "Grade added Successfuly",
                'grades'   => $grades
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
        'grade'  => 'required',
        'grade_label'  => 'required',
        'mark1'  => 'required',
        'mark2'  => 'required',
        'point'  => 'required',
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
            $grade_id = $request->grade_id;
            $grade = $request->grade;
            $og_grade = $request->og_grade;
            $mark1 = $request->mark1;
            $mark2 = $request->mark2;
            $point = $request->point;
            $grade_label = $request->grade_label;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'grade' => $grade,
                'grade_label' => $grade_label,
                'mark1' => $mark1,
                'mark2' => $mark2,
                'point' => $point,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'Class edited from the name of '.$og_grade.' to '.$grade
            ];

            grade::where(['id'=>$grade_id])->update($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $grades = Grade::orderBy('grades.level_id','asc')
            ->join('levels','grades.level_id', '=', 'levels.id')
            ->select('grades.id','grades.mark2','grades.mark1','grades.grade','grades.grade_label','grades.point','levels.level')
            ->get();

            $response = [
                'success' => true,
                'message' => "Grade edited Successfuly",
                'grades'   => $grades
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
            $grade_id = $request->grade_id;
            $grade = $request->grade;
        
            if($role_id < 4){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $log = [
                'user_id' => $user_id,
                'log' => 'Delete the grade by the name of '.$grade.', its id was '.$grade_id,
            ];
    
            Grade::where(['id' => $grade_id])->delete();
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $grades = Grade::orderBy('grades.level_id','asc')
            ->join('levels','grades.level_id', '=', 'levels.id')
            ->select('grades.id','grades.mark2','grades.mark1','grades.grade','grades.grade_label','grades.point','levels.level')
            ->get();
    
            $response = [
                'success' => true,
                'message' => "Class deleted Successfuly",
                'grades'   => $grades
            ];
    
            return response()->json($response, 200);
        
        } catch (\Throwable $th) {
            //return $th;
            return response()->json(['success' => false,
                'message' => ['Database or Server Errors']], 200);
        }
    }
}
