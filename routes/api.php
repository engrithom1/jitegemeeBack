<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamMarksController;
use App\Http\Controllers\HostExamMarksController;
use App\Http\Controllers\genderController;
use App\Http\Controllers\RelationToController;
use App\Http\Controllers\AdmissionTypeController;
use App\Http\Controllers\EntryTypeController;
use App\Http\Controllers\HealthStatusController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\DepositSlipController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashbordController;
use App\Http\Controllers\ParentStatusController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
 
    return "Cleared!";
 
 });

///depat
Route::get('/departments',[DepartmentController::class,'index']);
///staff
Route::get('/staffs',[StaffController::class,'index']);
///roles
Route::get('/roles',[RoleController::class,'index']);
///genders
Route::get('/genders',[genderController::class,'index']);
///genders
Route::get('/healths',[HealthStatusController::class,'index']);
///entry
Route::get('/entrys',[EntryTypeController::class,'index']);
///relations
Route::get('/relations',[RelationToController::class,'index']);
///relations
Route::get('/admissions',[AdmissionTypeController::class,'index']);
///exams
Route::get('/exams',[ExamController::class,'index']);
///exams
Route::get('/active_exams',[ExamController::class,'activeExams']);
//levels
Route::get('/levels',[LevelController::class,'index']);
Route::get('/all-levels',[LevelController::class,'allLevels']);
////subject
Route::get('/subjects',[SubjectController::class,'index']);
Route::get('/subjects-level',[SubjectController::class,'indexLevel']);
Route::get('/alevel-subjects',[SubjectController::class,'alevelSubjects']);
Route::get('/olevel-subjects',[SubjectController::class,'olevelSubjects']);
////class_subject
Route::post('/class_subjects',[SubjectController::class,'classSubjects']);
//classes 
Route::get('/class',[ClassroomController::class,'index']);
Route::get('/class-teachers',[ClassroomController::class,'classTeachers']);
Route::post('/class_level',[ClassroomController::class,'classLevel']);
//courses
Route::get('/courses',[CourseController::class,'index']);
//grade
Route::get('/grades',[GradeController::class,'index']);
//feee 
Route::get('/fees',[FeeController::class,'index']);
Route::get('/level-fees',[FeeController::class,'levelFees']);
Route::get('/fee-status',[FeeController::class,'feeStatus']);
//parents
Route::get('/parents',[ParentController::class,'index']);
//parent status
Route::get('/parent-status',[ParentStatusController::class,'index']);
/////dashbord-datas
Route::post('/dash-attendance',[DashbordController::class,'dashAttendance']);
Route::get('/dash-balances',[DashbordController::class,'dashBalances']);
Route::get('/dash-students',[DashbordController::class,'dashStudents']);
Route::get('/dash-teachers',[DashbordController::class,'dashTeachers']);
Route::get('/dash-staffs',[DashbordController::class,'dashStaffs']);
Route::get('/dash-subjects',[DashbordController::class,'dashSubjects']);
Route::post('/dash-paids',[DashbordController::class,'dashPaids']);
Route::post('/dash-debits',[DashbordController::class,'dashDebits']);
/////students /pending_students
Route::get('/pending_students',[StudentController::class,'pendingStudents']);
/////students /proposed-index-no
Route::get('/proposed-index-no',[StudentController::class,'proposedIndexNo']);



////this Routes need authentication token to gooo
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout',[AuthController::class, 'logout']);
    //exams /change-exam-status
    Route::post('/create-exam',[ExamController::class,'store']);
    Route::post('/delete-exam',[ExamController::class,'destroy']);
    Route::post('/update-exam',[ExamController::class,'update']);
    Route::post('/change-exam-status',[ExamController::class,'changeExamStatus']);
    //exam mark record
    Route::post('/create-exam-mark',[ExamMarksController::class,'store']);
    Route::post('/update-exam-mark',[ExamMarksController::class,'update']);
    Route::post('/student_mark_recorded',[ExamMarksController::class,'studentMarkRecorded']);
    //host exams 
    Route::post('/host-exam-mark',[HostExamMarksController::class,'hostExamMarks']);
    Route::post('/fetch_exam_results',[HostExamMarksController::class,'fetchResults']);
    ////subject
    Route::post('/create-subject',[SubjectController::class,'store']);
    Route::post('/delete-subject',[SubjectController::class,'destroy']);
    Route::post('/update-subject',[SubjectController::class,'update']);
    ////department
    Route::post('/create-department',[DepartmentController::class,'store']);
    Route::post('/delete-department',[DepartmentController::class,'destroy']);
    Route::post('/update-department',[DepartmentController::class,'update']);
    ////classs
    Route::post('/create-class',[ClassroomController::class,'store']);
    Route::post('/delete-class',[ClassroomController::class,'destroy']);
    Route::post('/update-class',[ClassroomController::class,'update']);
    ////course
    Route::post('/create-course',[CourseController::class,'store']);
    Route::post('/delete-course',[CourseController::class,'destroy']);
    Route::post('/update-course',[CourseController::class,'update']);
    ////grades
    Route::post('/create-grade',[GradeController::class,'store']);
    Route::post('/delete-grade',[GradeController::class,'destroy']);
    Route::post('/update-grade',[GradeController::class,'update']);
    ////grades
    Route::post('/create-fee',[FeeController::class,'store']);
    Route::post('/delete-fee',[FeeController::class,'destroy']);
    Route::post('/update-fee',[FeeController::class,'update']);
    ///student /finance-info
    Route::get('/all-students',[StudentController::class,'index']);
    Route::post('/create-student',[StudentController::class,'store']);
    Route::post('/class_students',[StudentController::class,'classStudents']);
    Route::post('/reflesh_students',[StudentController::class,'refleshStudents']);
    Route::post('/search_student_index_no',[studentController::class,'getStudentByIndexNo']);
    Route::post('/search_student_personal_info',[studentController::class,'getStudentPersonalInfo']);
    Route::post('/admission-info',[studentController::class,'getAdmissionInfo']);
    Route::post('/parent-info',[studentController::class,'getParentInfo']);
    Route::post('/finance-info',[studentController::class,'getFinanceInfo']);
    ///staff /staff-profile
    Route::post('/create-staff',[StaffController::class,'store']);
    Route::post('/staff-profile',[StaffController::class,'staffProfile']);
    Route::post('/update-about-me',[StaffController::class,'updateAboutMe']);
    ///parent
    Route::post('/create-parent',[ParentController::class,'store']);
    Route::post('/search-parent',[ParentController::class,'show']);

    ///feeepayments  addFeeToStudent 
    Route::post('/check_required_fees',[FeePaymentController::class,'checkRequiredFees']);
    Route::post('/add_fee_to_student',[FeePaymentController::class,'addFeeToStudent']);
    Route::post('/add_payment_to_student',[FeePaymentController::class,'addPaymentToStudent']);
    Route::post('/remove_fee_request',[FeePaymentController::class,'removeFeeRequest']);
    ////deposit slip
    Route::post('/add_deposit_slip',[DepositSlipController::class,'addDepositSlip']);
    ///atendance /attendance_records
    Route::post('/get_class_students',[AttendanceController::class,'getClassStudents']);
    Route::post('/submit_attendance',[AttendanceController::class,'addAttendance']);
    Route::post('/attendance_records',[AttendanceController::class,'getAttendanceRecords']);
    ////auth custom request 
    Route::post('/change-password',[AuthController::class,'changePassword']);
});


Route::controller(AuthController::class)->group(function(){
    Route::post('login','login');
    //Route::post('logout','logout');
});
