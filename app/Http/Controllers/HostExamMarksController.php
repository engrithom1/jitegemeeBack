<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\HostExamMarks;
use App\Models\ExamMarks;
use App\Models\ExamHost;
use App\Models\Student;
use DB;

class HostExamMarksController extends Controller
{
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetchResults(Request $request)
    {
      
        $year = $request->year;
        $exam_id = $request->exam_id;
        $level_id = $request->level_id;
        $class_id = $request->class_id;

        if($class_id == 0){
            $results = DB::table('host_exam_marks')
                    ->join('students', 'students.id', '=', 'host_exam_marks.student_id')
                    ->orderBy('host_exam_marks.total_marks','desc')
                    ->where(['host_exam_marks.level_id' => $level_id,'host_exam_marks.exam_id' => $exam_id,'host_exam_marks.year' => $year])
                    ->select('students.index_no','students.first_name','students.middle_name','students.last_name','host_exam_marks.total_marks','host_exam_marks.points','host_exam_marks.subjects','host_exam_marks.details')
                    ->get();
        }else{
            $results = DB::table('host_exam_marks')
                    ->join('students', 'students.id', '=', 'host_exam_marks.student_id')
                    ->orderBy('host_exam_marks.total_marks','desc')
                    ->where(['host_exam_marks.classroom_id' => $class_id,'host_exam_marks.level_id' => $level_id,'host_exam_marks.exam_id' => $exam_id,'host_exam_marks.year' => $year])
                    ->select('students.index_no','students.first_name','students.middle_name','students.last_name','host_exam_marks.total_marks','host_exam_marks.points','host_exam_marks.subjects','host_exam_marks.details')
                    ->get();
        }

        

        $response = [
            'success' => true,
            'message' => "Exam Reults Hosted Successfuly",
            'results' => $results
        ];

        return response()->json($response, 200);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function hostExamMarks(Request $request)
    {
      
        $year = $request->year;
        $subl = $request->subl;
        $user_id = $request->user_id;
        $role_id = $request->role_id;
        $exam_id = $request->exam_id;
        $level_id = $request->level_id;
        $class_id = $request->class_id;

        if($role_id != 4){
            return response()->json(['success' => false,
            'message' => 'You not allowed to make this action'], 200); 
        }

        $students = Student::where(['classroom_id' => $class_id,'accademic_year' => $year])->select('id')->get();
        $stdz = [];

        foreach($students as $student){
            $id = $student->id;
            $details = "";
            $t_marks = 0;
            $row_arr = [];
            $ptz = array();

            $marks_data = DB::table('exam_marks')
                          ->join('subjects', 'subjects.id', '=', 'exam_marks.subject_id')
                          ->orderBy('exam_marks.subject_id','asc')
                          ->where(['level_id' => $level_id,'year' => $year,'exam_id' => $exam_id, 'student_id' => $id])
                          ->select('exam_marks.grade','exam_marks.grade_point','exam_marks.grade_label','exam_marks.grade_point','exam_marks.mark','subjects.subject','exam_marks.id','exam_marks.user_id')
                          ->get();

            foreach($marks_data as $m_data){
                ///details
                $gl = ucfirst($m_data->grade);
                $details .= ucfirst($m_data->subject."-".$m_data->mark."(".$gl."), ");
                ///total marks
                $t_marks += $m_data->mark;
                ///points
                $ptz[] = $m_data->grade_point;

            }

            sort($ptz);
            array_splice($ptz, -2);
            

            $row_arr = [
                'details' => $details,
                'total_marks' => $t_marks,
                'student_id' => $id,
                'level_id' => $level_id,
                'user_id'  => $user_id,
                'exam_id' => $exam_id,
                'year' => $year,
                'subjects' => $subl,
                'classroom_id' => $class_id,
                'points' => array_sum($ptz)
            ];
            

            array_push($stdz, $row_arr);
        }

        $exammarks = [
            'user_id' => $user_id,
            'year' => $year,
            'exam_id' => $exam_id,
            'level_id' => $level_id,
            'classroom_id' => $class_id,
        ];
       
        DB::table('host_exam_marks')->insert($stdz);
        ExamHost::create($exammarks);
        ExamMarks::where(['exam_id'=>$exam_id,'year'=>$year, 'classroom_id'=>$class_id,'subs' => 0])->update(['subs' => $subl]);
       
        $response = [
            'success' => true,
            'message' => "Exam Reults Hosted Successfuly",
        ];

        return response()->json($response, 200);
 
    }

}
