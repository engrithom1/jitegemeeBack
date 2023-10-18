<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\Exam;
use App\Models\ExamMarks;
use App\Models\User;
use App\Models\Grade;
use DB;

class ExamMarksController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function studentMarkRecorded(Request $request){

        $year = $request->year;
        $student_id = $request->student_id;
        $exam_id = $request->exam_id;
        $level_id = $request->level_id;

        return DB::table('exam_marks')
                ->join('subjects', 'subjects.id', '=', 'exam_marks.subject_id')
                ->where(['exam_marks.year' => $year, 'exam_marks.exam_id' => $exam_id, 'exam_marks.student_id' => $student_id, 'exam_marks.level_id' => $level_id])
                ->select('exam_marks.grade','exam_marks.grade_point','exam_marks.grade_label','exam_marks.subs','exam_marks.mark','subjects.subject','exam_marks.subject_id','exam_marks.id','exam_marks.user_id')
                ->get();  
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
        //'user_id' => 'required',
        'exam_id' => 'required',
        //'level_id' => 'required',
        //'classroom_id' => 'required',
        'subject_id' => 'required',
        'student_id' => 'required',
        /*'grade_id' => 'required',
        'grade' => 'required',
        'grade_label' => 'required',
        'grade_point' => 'required',*/
        'mark' => 'required',
        'year' => 'required',
       ]);

       if($validator->fails()){
        $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
        return response()->json($response, 200);

    }else{
        
        $mark = $request->mark;
        $year = $request->year;
        $student_id = $request->student_id;
        $subject_id = $request->subject_id;
        $user_id = $request->user_id;
        $role_id = $request->role_id;
        $exam_id = $request->exam_id;
        $level_id = $request->level_id;
        $classroom_id = $request->class_id;

        if($role_id < 2){
            return response()->json(['success' => false,
            'message' => 'You not allowed to make this action'], 200); 
        }

        $mark_exist = ExamMarks::where(['year' => $year, 'exam_id' => $exam_id, 'student_id' => $student_id, 'level_id' => $level_id, 'subject_id' => $subject_id])
                               ->get();
        $grade_exist = Grade::where(['level_id' => $level_id])
                            ->where('mark1','<=', $mark)
                            ->where('mark2','>=', $mark)
                            ->get();

        $exams = DB::table('exam_marks')
                    ->join('subjects', 'subjects.id', '=', 'exam_marks.subject_id')
                    ->where(['exam_marks.year' => $year, 'exam_marks.exam_id' => $exam_id, 'exam_marks.student_id' => $student_id, 'exam_marks.level_id' => $level_id])
                    ->select('exam_marks.grade','exam_marks.grade_point','exam_marks.grade_label','exam_marks.subs','exam_marks.mark','subjects.subject','exam_marks.subject_id','exam_marks.id','exam_marks.user_id')
                    ->get();                    

        if(count($mark_exist) != 0){

            return response()->json(['success' => false,
            'message' => 'Mark aleady added','examx' => $exams], 200);
        }

        if(count($grade_exist) === 0){

            return response()->json(['success' => false,
            'message' => 'No Grade defined for a mark','examx' => $exams], 200);
        }

                             
        $grade_id = $grade_exist[0]->id;
        $grade = $grade_exist[0]->grade;
        $grade_point = $grade_exist[0]->point;
        $grade_label = $grade_exist[0]->grade_label;
        

        $exammarks = [
            'user_id' => $user_id,
            'mark' => $mark,
            'year' => $year,
            'subs' => 0,
            'student_id' => $student_id,
            'subject_id' => $subject_id,
            'role_id' => $role_id,
            'exam_id' => $exam_id,
            'level_id' => $level_id,
            'classroom_id' => $classroom_id,
            'grade_id' => $grade_id,
            'grade' => $grade,
            'grade_point' => $grade_point,
            'grade_label' => $grade_label,
        ];

        //return $exammarks;

        /*$log = [
            'user_id' => $user_id,
            'log' => 'Create the Exam by the name of '.$exammarkname
        ];*/

        ExamMarks::create($exammarks);
        //app('App\Http\Controllers\LogController')->storeLogs($log);

        $exams = DB::table('exam_marks')
                ->join('subjects', 'subjects.id', '=', 'exam_marks.subject_id')
                ->where(['exam_marks.year' => $year, 'exam_marks.exam_id' => $exam_id, 'exam_marks.student_id' => $student_id, 'exam_marks.level_id' => $level_id])
                ->select('exam_marks.grade','exam_marks.grade_point','exam_marks.grade_label','exam_marks.subs','exam_marks.mark','subjects.subject','exam_marks.subject_id','exam_marks.id','exam_marks.user_id')
                ->get();      

        $response = [
            'success' => true,
            'message' => "Exam added Successfuly",
            'examx'   => $exams
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

            $user_id = $request->user_id;
            $level_id = $request->level_id;
            $change_user_id = $request->change_user_id;
            $role_id = $request->role_id;
            $mark = $request->mark;
            $id = $request->id;
            $exam_id = $request->exam_id;
            $student_id = $request->student_id;
            $year = $request->year;
            
            if($role_id < 2){
                return response()->json(['success' => false,
                'message' => 'You not allowed to make this action'], 200); 
            }

            if($user_id !== $change_user_id){
                return response()->json(['success' => false,
                'message' => 'your not alowed to make this change'], 200); 
            }

            $grade_exist = Grade::where(['level_id' => $level_id])
                            ->where('mark1','<=', $mark)
                            ->where('mark2','>=', $mark)
                            ->get();

            if(count($grade_exist) === 0){

                return response()->json(['success' => false,
                'message' => 'No Grade defined for a mark','examx' => $exams], 200);
            }
                        
            $grade_id = $grade_exist[0]->id;
            $grade = $grade_exist[0]->grade;
            $grade_point = $grade_exist[0]->point;
            $grade_label = $grade_exist[0]->grade_label;

            $exammark = [
                'mark' => $mark,
                'grade_id' => $grade_id,
                'grade' => $grade,
                'grade_point' => $grade_point,
                'grade_label' => $grade_label,
            ];

            /*$log = [
                'user_id' => $user_id,
                'log' => 'Exam edited from the name of '.$og_examname.' to '.$examname
            ];*/

            ExamMarks::where(['id'=>$id])->update($exammark);
            //app('App\Http\Controllers\LogController')->storeLogs($log);

            $examx = DB::table('exam_marks')
                ->join('subjects', 'subjects.id', '=', 'exam_marks.subject_id')
                ->where(['exam_marks.year' => $year, 'exam_marks.exam_id' => $exam_id, 'exam_marks.student_id' => $student_id, 'exam_marks.level_id' => $level_id])
                ->select('exam_marks.grade','exam_marks.grade_point','exam_marks.grade_label','exam_marks.subs','exam_marks.mark','subjects.subject','exam_marks.subject_id','exam_marks.id','exam_marks.user_id')
                ->get();

            $response = [
                'success' => true,
                'message' => "Exam Marks edited Successfuly",
                'examx'   => $examx
            ];

            return response()->json($response, 200);
        
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
