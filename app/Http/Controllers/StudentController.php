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
use App\Models\FeePayment;
use App\Models\Fee;
use App\Models\FeeBalance;
use DB;


class StudentController extends Controller
{
    /**$paids = FeePayment::where('year',$year)->sum('paid_amount');
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function proposedIndexNo()
    {
        return Student::max('index_no') + 1;
    }
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function pendingStudents()
    {
        $students = DB::table('students')
        ->join('student_statuses','student_statuses.id','=','studentS.student_status_id')
        ->select('students.id','students.admission','students.index_no','students.first_name','students.middle_name','students.last_name','students.classroom_id','students.level_id','students.accademic_year','student_statuses.status_name')
        ->where(['students.classroom_id' => 0])
        ->get();

        $response = [
        'success' => true,
        'message' => "Class added Successfuly",
        'students'  => $students,
        ];

        return response()->json($response, 200);
    }

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
     * Store a newly created resource in storage. getFinanceInfo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFinanceInfo(Request $request)
    {
        $student_id = $request->student_id;
        $year = $request->year;

        $balance = FeeBalance::where(['student_id' => $student_id])->first();
        $feepays = DB::table('fee_payments')
                    ->join('fees','fees.id','=','fee_payments.fee_id')
                    ->select('fees.id AS f_id','fees.fee','fee_payments.id','fee_payments.amount','fee_payments.paid_amount','fee_payments.status')
                    ->where(['fee_payments.year' => $year,'fee_payments.student_id' => $student_id])->get();

        $response = [
            'success' => true,
            'message' => "Succesfully Paid..",
            'feepays'  => $feepays,
            'balance' =>  $balance['amount']
        ];
        
        return response()->json($response, 200);
    } 

            /**
     * Store a newly created resource in storage. getFinanceInfo
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAdmissionInfo(Request $request)
    {
        $student_id = $request->student_id;

        return DB::table('students')
                ->join('student_statuses','student_statuses.id','=','students.student_status_id')
                ->join('classrooms','classrooms.id','=','students.classroom_id')
                ->join('levels','levels.id','=','students.level_id')
                ->join('admission_types','admission_types.id','=','students.admission')
                ->join('entry_types','entry_types.id','=','students.entry')
                ->select('students.id','students.regist_year','student_statuses.status_name','students.accademic_year','students.created_at','students.prem_no','students.index_no','admission_types.admission','levels.level','classrooms.classname',
                'entry_types.id AS entry_id','entry_types.entry','students.school_from','students.transfer_reason')
                ->where(['students.id' => $student_id])
                ->first();

    } 
         /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getParentInfo(Request $request)
    {
        $student_id = $request->student_id;

        return DB::table('students')
                ->join('parents','parents.id','=','students.parent_id')
                ->join('relation_tos','relation_tos.id','=','students.relation_to')
                ->join('genders','genders.id','=','parents.gender')
                ->select('students.id','parents.first_name','parents.middle_name','parents.last_name','parents.photo','parents.home_address','parents.phone',
                'parents.email','parents.occupation','relation_tos.relation','parents.nationality','genders.gender')
                ->where(['students.id' => $student_id])
                ->first();

    } 

           /**getParentInfo
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStudentPersonalInfo(Request $request)
    {
        $index_no = $request->index_no;

        $student = DB::table('students')
                          ->join('student_statuses','student_statuses.id','=','students.student_status_id')
                          ->join('parent_statuses','parent_statuses.id','=','students.parent_status_id')
                          ->join('genders','genders.id','=','students.gender')
                          ->join('health_statuses','health_statuses.id','=','students.hearth')
                          ->select('students.id','students.index_no','students.email','students.first_name','students.middle_name','students.last_name','students.classroom_id','students.level_id','students.accademic_year',
                          'student_statuses.status_name','parent_statuses.parent_status','students.behavior','health_statuses.health','genders.gender','students.phone','students.photo','students.home_address','students.nationality','students.birth_date')
                          ->where(['students.index_no' => $index_no])
                          ->first();

        $response = [
            'success' => true,
            'message' => "Student featched Successfuly",
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
                          ->join('student_statuses','student_statuses.id','=','students.student_status_id')
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
            'prem_no' =>  'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'nationality' => 'required',
            'gender' => 'required',
            'home_address' => 'required',
            'accademic_year' => 'required',
            'regist_year' => 'required',
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

            $created_at = date('Y-m-d');

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
                'regist_year' => $request->accademic_year,
                'photo' => $request->photo,
                'user_id' => $request->user_id,
                'index_no' => $request->index_no,
                'prem_no' => $request->prem_no,
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
                'religion' => $request->religion,
                'former_school' => $request->former_school,
                'birth_place' => $request->birth_place,
                'nationality' => $request->nationality,
                'email' => $email,
                'student_status_id' => 1,
                'parent_status_id' => $request->parent_status_id,
                'created_at' => $created_at

            ];

            $user = [
                'username' => $request->index_no,
                'role_id' => 1,
                'type' => "student",
                'index_no' => $request->index_no,
                'password' => $password,
            ];

            try {
                $student_id = Student::insertGetId($student);
                User::create($user);

                /////////take care about payments//////////////////////////
                $fees = $request->fees;
                $fz = explode(',',$fees);

                $year = $request->accademic_year;
                $level_id = $request->level_id;
                $class_id = $request->classroom_id;
                $user_id = $request->user_id;

                $feepay = DB::table('fee_payments')
                    ->join('fees','fees.id','=','fee_payments.fee_id')
                    ->select('fees.id AS f_id','fees.fee','fee_payments.id','fee_payments.amount','fee_payments.paid_amount','fee_payments.status')
                    ->where(['fee_payments.year' => $year, 'fee_payments.level_id' => $level_id, 'fee_payments.student_id' => $student_id])->get();

                ////take care of balance man
                $student_balance = FeeBalance::where(['student_id' => $student_id])->get();

                if(count($student_balance) === 0){
                    FeeBalance::create(['amount' => 0,'student_id'=>$student_id,'user_id'=>$user_id]);
                }

                if(count($feepay) === 0){
                    ///action where payments hazipo
                    ///select fees on class
                   
                    $fzs = Fee::whereIn('id',$fz)->get();
                    $stdz = [];
        
                    foreach($fzs as $fe){
                        $row_arr = [];
        
                        $row_arr = [
                            'valid_to' => $year,
                            'amount' => $fe->amount,
                            'paid_amount' => 0,
                            'student_id' => $student_id,
                            'level_id' => $level_id,
                            'user_id'  => $user_id,
                            'year' => $year,
                            'created_at' => $created_at,
                            'status' => 0,
                            'classroom_id' => $class_id,
                            'fee_id' => $fe->id
                        ];
                        
            
                        array_push($stdz, $row_arr);
        
                    }
                    
                        DB::table('fee_payments')->insert($stdz);
                }

                $response = [
                    'success' => true,
                    'message' => $request->first_name." ".$request->last_name." added Successfuly"
                ];

                return response()->json($response, 200);

            } catch (\Throwable $th) {
                return $th;
                return response()->json(['success' => false,
                'message' => ['Database error']], 200);
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
