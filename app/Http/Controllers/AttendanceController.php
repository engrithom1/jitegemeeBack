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
use App\Models\Role;
use App\Models\Parento;
use App\Models\Student;
use App\Models\ExamMarks;
use App\Models\Attendance;
use App\Models\SessionAtendance;
use DB;

class AttendanceController extends Controller
{
         /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getClassStudents(Request $request)
    {
        $class_id = $request->class_id;

        $students = Student::where(['classroom_id' => $class_id])
               ->select('first_name','middle_name','last_name','id','index_no')
               ->get();

               $response = [
                'success' => true,
                'message' => "Class added Successfuly",
                'students' =>  $students
            ];
            
        return response()->json($response, 200);

    }

             /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAttendanceRecords(Request $request)
    {
        $role_id = $request->role_id;
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $class_id = $request->class_id;

        if($role_id < 2){
            return response()->json(['success' => false,
            'message' => ['You not allowed to make this action']], 200); 
        }

        $a_students = DB::table('attendances')
          ->join('students','students.id','=','attendances.student_id')
          ->orderBy('attendances.date_no','asc')
          ->where(['attendances.classroom_id' => $class_id])
          ->whereBetween('attendances.date_no',[$date_from, $date_to])
          ->select('students.id AS std_id','students.index_no','students.first_name','students.middle_name','students.last_name','attendances.attend','attendances.date_att')
          ->get();

        return response()->json(['success' => true,
        'a_students' => $a_students,
        'message' => ['Attendence has been fetched successfully']], 200);
       
    }

            /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAttendance(Request $request)
    {
        $role_id = $request->role_id;
        $subject_id = $request->subject_id;
        $date_no = $request->data_att;
        $classroom_id = $request->class_id;
        $class_att = $request->class_att;
        $session_att = $request->session_att;
        $att_type = $request->att_type;

        if($role_id < 2){
            return response()->json(['success' => false,
            'message' => ['You not allowed to make this action']], 200); 
        }

        $fnd = Attendance::where(['classroom_id' => $classroom_id, 'date_no' => $date_no])->get();
        $att_ss = SessionAtendance::where(['classroom_id' => $classroom_id,'subject_id' => $subject_id, 'date_no' => $date_no])->get();
        
        ///session attendance
        if($att_type == 1){
            if(count($att_ss) != 0){
                return response()->json(['success' => false,
                'message' => ['Attendence is aleady been created for a particular Date']], 200); 
            }else{
    
                DB::table('session_atendances')->insert($session_att);
    
                return response()->json(['success' => true,
                'message' => ['Attendence has been submitted successfully']], 200);
            }
        }
        ////class attendance
        if($att_type == 2){
            if(count($fnd) != 0){
                return response()->json(['success' => false,
                'message' => ['Attendence is aleady been created for a particular Date']], 200); 
            }else{
    
                DB::table('attendances')->insert($class_att);
    
                return response()->json(['success' => true,
                'message' => ['Attendence has been submitted successfully']], 200);
            }
        }

        if($att_type == 3){

                if(count($fnd) != 0 && count($att_ss) != 0){
                    return response()->json(['success' => false,
                    'message' => ['Attendence is aleady been created for a particular Date']], 200); 
                }elseif(count($fnd) != 0 || count($att_ss) != 0){
                    return response()->json(['success' => false,
                    'message' => ['One of Attendence is aleady been taken is better to be specific']], 200); 
                }else{
        
                    DB::table('attendances')->insert($class_att);
                    DB::table('session_atendances')->insert($session_att);
        
                    return response()->json(['success' => true,
                    'message' => ['Both Attendence has been submitted successfully']], 200);
                }

        }
        
       
    }
}
