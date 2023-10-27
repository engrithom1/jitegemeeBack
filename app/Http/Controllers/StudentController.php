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
use DB;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

           /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStudentInfo(Request $request)
    {
        $index_no = $request->index_no;

        $student = DB::table('students')
                          ->join('student_statuses','student_statuses.id','=','students.student_status_id')
                          ->select('students.id','students.index_no','students.first_name','students.middle_name','students.last_name','students.classroom_id','students.level_id','students.accademic_year',
                          'student_statuses.status_name','students.phone','students.photo','students.home_address','students.nationality','students.birth_date')
                          ->where(['students.index_no' => $index_no])
                          ->first();

        $response = [
            'success' => true,
            'message' => "Class added Successfuly",
            'student'  => $student,
        ];

        return response()->json($response, 200);
    } 


       /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStudentByIndexNo(Request $request)
    {
        $index_no = $request->index_no;

        $student = DB::table('students')
                          ->join('student_statuses','student_statuses.id','=','studentS.student_status_id')
                          ->select('students.id','students.admission','students.index_no','students.first_name','students.middle_name','students.last_name','students.classroom_id','students.level_id','students.accademic_year','student_statuses.status_name')
                          ->where(['students.index_no' => $index_no])
                          ->first();

        $response = [
            'success' => true,
            'message' => "Class added Successfuly",
            'student'  => $student,
        ];

        return response()->json($response, 200);
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function classStudents(Request $request)
    {
        $class_id = $request->class_id;
        $exam_id = $request->exam_id;
        $year = $request->year;
        $subjects = $request->subjects;

        $subjectz = explode(',',$subjects);
        try {

            $students = Student::where(['classroom_id' => $class_id])
                        ->withCount(['exam_marks'=> function($query) use($exam_id,$year,$class_id){
                          $query->where(['exam_marks.exam_id'=> $exam_id, 'exam_marks.year' => $year]);
                        }])->get();

            /*return Student::withCount('exam_marks')
                          ->get();DB::table('students')
            ->join('exam_marks', 'students.id', '=', 'exam_marks.student_id')
            ->select('students.id','students.first_name')
            ->withCount('exam_marks')
            ->where(['exam_marks.exam_id'=> $exam_id, 'exam_marks.year' => $year, 'students.classroom_id' => $class_id])
            ->get();*/
            

            $subjects = DB::table('subjects')->whereIn('id',$subjectz )->get();
            
            $hosted_ = ExamMarks::where(['exam_id' => $exam_id, 'year' => $year, 'classroom_id' => $class_id,'subs' => 0])->get();
            
            $hosted = false;

            if(count($hosted_)){
                $hosted = true;
            }

            //return $hosted;

            $response = [
                'success' => true,
                'message' => "Class added Successfuly",
                'subjects'   => $subjects,
                'students'   => $students,
                'hosted' => $hosted
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
    public function refleshStudents(Request $request){
        $class_id = $request->class_id;
        $exam_id = $request->exam_id;
        $year = $request->year;
  
        try {

            $students = Student::where('classroom_id',$class_id)
                        ->withCount(['exam_marks'=> function($query) use($exam_id,$year,$class_id){
                          $query->where(['exam_marks.exam_id'=> $exam_id, 'exam_marks.year' => $year]);
                        }])->get();

            $hosted_ = ExamMarks::where(['exam_id' => $exam_id, 'year' => $year, 'classroom_id' => $class_id,'subs' => 0])->get();

            $hosted = false;

            if(count($hosted_)){
                $hosted = true;
            }

            $response = [
                'success' => true,
                'message' => "Class added Successfuly",
                'students'   => $students,
                'hosted' => $hosted
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
            'index_no' =>  ['required', 'string', 'max:255', 'unique:students'],
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'nationality' => 'required',
            'gender' => 'required',
            'home_address' => 'required',
            'accademic_year' => 'required',
            'user_id' => 'required',
            'birth_date' => 'required',
            'hearth' => 'required',
            'relation_to' => 'required',
            'level_id' => 'required',
            'classroom_id' => 'required',
            'entry' => 'required',
            'admission' => 'required',
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }else{

            $role_id = $request->role_id;

            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => 'You not allowed to make this action'], 200); 
            }

            $password = bcrypt($request->parent_phone);
            $behavior = $request->behavior;
            $transfer_reason = $request->transfer_reason;
            $school_from = $request->school_from;
            $phone = $request->phone;
            $email = $request->email;

            if($behavior == null){
                $behavior = "no comment";
            }
            if($transfer_reason == null){
                $transfer_reason = "no comment";
            }
            if($school_from == null){
                $school_from = "no school";
            }
            if($phone == null){
                $phone = $request->parent_phone;
            }
            if($email == null){
                $email = "no email";
            }

            $student = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'admission' => $request->admission,
                'gender' => $request->gender,
                'phone' => $phone,
                'home_address' => $request->home_address,
                'accademic_year' => $request->accademic_year,
                'photo' => $request->photo,
                'user_id' => $request->user_id,
                'index_no' => $request->index_no,
                'birth_date' => $request->birth_date,
                'behavior' => $behavior,
                'hearth' => $request->hearth,
                'school_from' => $school_from,
                'transfer_reason' => $transfer_reason,
                'relation_to' => $request->relation_to,
                'level_id' => $request->level_id,
                'classroom_id' => $request->classroom_id,
                'parent_id' => $request->parent_id,
                'entry' => $request->entry,
                'nationality' => $request->nationality,
                'email' => $email,
                'student_status_id' => 1,
                'parent_status_id' => $request->parent_status_id,

            ];

            $user = [
                'username' => $request->index_no,
                'role_id' => 1,
                'type' => "student",
                'index_no' => $request->index_no,
                'password' => $password,
            ];

            try {
                Student::create($student);
                User::create($user);

                $response = [
                    'success' => true,
                    'message' => $request->initial." ".$request->first_name." ".$request->last_name." added Successfuly"
                ];

                return response()->json($response, 200);

            } catch (\Throwable $th) {
                return $th;
                /*return response()->json(['success' => false,
                'message' => ['Database error']], 200);*/
            }

            
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
