<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Course;
use App\Models\Classroom;
use Illuminate\Http\Request;

class CourseController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return Course::get();
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
        'coursename' => ['required', 'string', 'max:255', 'unique:courses'],
        'subjects'  => 'required'
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
            $coursename = $request->coursename;
            $subjects = $request->subjects;
            $subject_names = $request->subject_names;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'coursename' => $coursename,
                'subjects' => $subjects, 
                'subject_names' => $subject_names,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'Create the course by the name of '.$coursename
            ];

            Course::create($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $courses = Course::get();

            $response = [
                'success' => true,
                'message' => "Course added Successfuly",
                'courses'   => $courses
            ];

            return response()->json($response, 200);
        }
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
        'coursename' => ['required', 'string', 'max:255'],
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
            $coursename = $request->coursename;
            $subject_names = $request->subject_names;
            $subjects = $request->subjects;
            $course_id = $request->course_id;
            $og_coursename = $request->og_coursename;

            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'coursename' => $coursename,
                'subject_names' => $subject_names,
                'subjects' => $subjects,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'Course edited from the name of '.$og_coursename.' to '.$coursename
            ];

            Course::where(['id'=>$course_id])->update($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $courses = Course::get();

            $response = [
                'success' => true,
                'message' => "Course edited Successfuly",
                'courses'   => $courses
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
            $class_id = $request->id;
            $coursename = $request->coursename;
        
            if($role_id < 4){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $log = [
                'user_id' => $user_id,
                'log' => 'Delete the Course by the name of '.$coursename.', its id was '.$class_id,
            ];
    
            Course::where(['id' => $class_id])->delete();
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $courses = Course::get();
    
            $response = [
                'success' => true,
                'message' => "Course deleted Successfuly",
                'courses'   => $courses
            ];
    
            return response()->json($response, 200);
        
        } catch (\Throwable $th) {
            //return $th;
            return response()->json(['success' => false,
                'message' => ['Database or Server Errors']], 200);
        }
    }

}
