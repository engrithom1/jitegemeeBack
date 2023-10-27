<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Level;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$students = Student::where('classroom_id',$class_id)
                        ->withCount(['exam_marks'=> function($query) use($exam_id,$year,$class_id){
                          $query->where(['exam_marks.exam_id'=> $exam_id, 'exam_marks.year' => $year]);
                        }])->get();*/

        try {
            return Classroom::orderBy('classrooms.level_id','asc')
            ->join('levels','classrooms.level_id', '=', 'levels.id')
            ->select('classrooms.id','classrooms.classname','classrooms.subjects','classrooms.fees','classrooms.level_id','classrooms.roomnumber','classrooms.students','levels.level')
            ->withCount('students')
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
    public function classLevel(Request $request)
    {
        $level_id = $request->level_id;
        try {
            $claszs = Classroom::where(['level_id'=>$level_id])->get();

            $response = [
                'success' => true,
                'message' => "Class added Successfuly",
                'claszs'   => $claszs
            ];

            return response()->json($response, 200);
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
        'classname' => ['required', 'string', 'max:255', 'unique:classrooms'],
        'roomnumber'  => 'required',
        'subjects'  => 'required',
        'fees'  => 'required',
        'level_id'  => 'required',
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
            $classname = $request->classname;
            $roomnumber = $request->roomnumber;
            $fees = $request->fees;
            $subjects = $request->subjects;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'classname' => $classname,
                'roomnumber' => $roomnumber,
                'fees' => $fees,
                'subjects' => $subjects,
                'level_id' => $level_id,
                
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'Create the classname by the name of '.$classname
            ];

            Classroom::create($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $claszs = Classroom::orderBy('classrooms.level_id','asc')
            ->join('levels','classrooms.level_id', '=', 'levels.id')
            ->select('classrooms.id','classrooms.classname','classrooms.roomnumber','classrooms.students','levels.level')
            ->get();

            $response = [
                'success' => true,
                'message' => "Class added Successfuly",
                'claszs'   => $claszs
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
        'classname' => ['required', 'string', 'max:255'],
        'roomnumber'  => 'required',
        'fees'  => 'required',
        'subjects'  => 'required',
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
            $classname = $request->classname;
            $roomnumber = $request->roomnumber;
            $fees = $request->fees;
            $subjects = $request->subjects;
            $class_id = $request->class_id;
            $og_classname = $request->og_classname;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'classname' => $classname,
                'roomnumber' => $roomnumber,
                'fees' => $fees,
                'subjects' => $subjects,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'Class edited from the name of '.$og_classname.' to '.$classname
            ];

            Classroom::where(['id'=>$class_id])->update($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $claszs = Classroom::orderBy('classrooms.level_id','asc')
            ->join('levels','classrooms.level_id', '=', 'levels.id')
            ->select('classrooms.id','classrooms.classname','classrooms.roomnumber','classrooms.students','levels.level')
            ->get();

            $response = [
                'success' => true,
                'message' => "Class edited Successfuly",
                'claszs'   => $claszs
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
            $class_id = $request->class_id;
            $classname = $request->classname;
        
            if($role_id < 4){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $log = [
                'user_id' => $user_id,
                'log' => 'Delete the classname by the name of '.$classname.', its id was '.$class_id,
            ];
    
            Classroom::where(['id' => $class_id])->delete();
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $claszs = Classroom::orderBy('classrooms.level_id','asc')
            ->join('levels','classrooms.level_id', '=', 'levels.id')
            ->select('classrooms.id','classrooms.classname','classrooms.roomnumber','classrooms.students','levels.level')
            ->get();
    
            $response = [
                'success' => true,
                'message' => "Class deleted Successfuly",
                'claszs'   => $claszs
            ];
    
            return response()->json($response, 200);
        
        } catch (\Throwable $th) {
            //return $th;
            return response()->json(['success' => false,
                'message' => ['Database or Server Errors']], 200);
        }
    }
}
