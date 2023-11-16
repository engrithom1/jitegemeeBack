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
    public function classTeachers()
    {
       
        try {
            return Classroom::orderBy('classrooms.level_id','asc')
            ->join('levels','classrooms.level_id', '=', 'levels.id')
            ->join('users','classrooms.teacher_id', '=', 'users.id')
            ->join('staff','staff.index_no', '=', 'users.index_no')
            ->select('staff.initial','staff.first_name','staff.last_name','classrooms.id','classrooms.classname','classrooms.subjects','classrooms.fees','classrooms.level_id','classrooms.roomnumber','classrooms.students','levels.level','classrooms.teacher_id','classrooms.course_id')
            ->withCount('students')
            ->get();
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
    public function index(){

        try {
            return Classroom::orderBy('classrooms.level_id','asc')
            ->join('levels','classrooms.level_id', '=', 'levels.id')
            ->select('classrooms.id','classrooms.classname','classrooms.subjects','classrooms.fees','classrooms.level_id','classrooms.roomnumber','classrooms.students','levels.level','classrooms.teacher_id','classrooms.course_id')
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
        'teacher_id'  => 'required',
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
            $teacher_id = $request->teacher_id;
            $course_id = $request->course_id;
            $level_id = $request->level_id;
            $classname = $request->classname;
            $roomnumber = $request->roomnumber;
            $fees = $request->fees;
            $subjects = $request->subjects;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            ///check teacher
            $teacher = Classroom::where(['teacher_id' => $teacher_id])->get();

            if(count($teacher) != 0){
                return response()->json(['success' => false,
                'message' => ["Teacher can't have more than one class"]], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'classname' => $classname,
                'teacher_id' => $teacher_id,
                'course_id' => $course_id,
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
            ->join('users','classrooms.teacher_id', '=', 'users.id')
            ->join('staff','staff.index_no', '=', 'users.index_no')
            ->select('staff.initial','staff.first_name','staff.last_name','classrooms.id','classrooms.classname','classrooms.subjects','classrooms.fees','classrooms.level_id','classrooms.roomnumber','classrooms.students','levels.level','classrooms.teacher_id','classrooms.course_id')
            ->withCount('students')
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
        'teacher_id'  => 'required',
        'course_id'  => 'required',
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
            $teacher_id = $request->teacher_id;
            $course_id = $request->course_id;
            $classname = $request->classname;
            $roomnumber = $request->roomnumber;
            $fees = $request->fees;
            $subjects = $request->subjects;
            $class_id = $request->class_id;
            $og_classname = $request->og_classname;

            $teacher = Classroom::where(['teacher_id' => $teacher_id])
                                ->where('id', '!=', $class_id)->get();

            if(count($teacher) != 0){
                return response()->json(['success' => false,
                'message' => ["Teacher can't have more than one class"]], 200); 
            }

            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'classname' => $classname,
                'teacher_id' => $teacher_id,
                'course_id' => $course_id,
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
            ->join('users','classrooms.teacher_id', '=', 'users.id')
            ->join('staff','staff.index_no', '=', 'users.index_no')
            ->select('staff.initial','staff.first_name','staff.last_name','classrooms.id','classrooms.classname','classrooms.subjects','classrooms.fees','classrooms.level_id','classrooms.roomnumber','classrooms.students','levels.level','classrooms.teacher_id','classrooms.course_id')
            ->withCount('students')
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
            ->join('users','classrooms.teacher_id', '=', 'users.id')
            ->join('staff','staff.index_no', '=', 'users.index_no')
            ->select('staff.initial','staff.first_name','staff.last_name','classrooms.id','classrooms.classname','classrooms.subjects','classrooms.fees','classrooms.level_id','classrooms.roomnumber','classrooms.students','levels.level','classrooms.teacher_id','classrooms.course_id')
            ->withCount('students')
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
