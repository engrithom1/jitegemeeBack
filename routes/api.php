<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamMarksController;
use App\Http\Controllers\HostExamMarksController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\RelationToController;
use App\Http\Controllers\AdmissionTypeController;
use App\Http\Controllers\EntryTypeController;
use App\Http\Controllers\HealthStatusController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\DepositSlipController;


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

///depat
Route::get('/departments',[DepartmentController::class,'index']);
///staff
Route::get('/staffs',[StaffController::class,'index']);
///roles
Route::get('/roles',[RoleController::class,'index']);
///genders
Route::get('/genders',[GenderController::class,'index']);
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
////subject
Route::get('/subjects',[SubjectController::class,'index']);
//classes
Route::get('/class',[ClassroomController::class,'index']);
//classes
Route::post('/class_level',[ClassroomController::class,'classLevel']);
//grade
Route::get('/grades',[GradeController::class,'index']);
//feee
Route::get('/fees',[FeeController::class,'index']);
//parents
Route::get('/parents',[ParentController::class,'index']);


////this Routes need authentication token to gooo
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout',[AuthController::class, 'logout']);
    //exams
    Route::post('/create-exam',[ExamController::class,'store']);
    Route::post('/delete-exam',[ExamController::class,'destroy']);
    Route::post('/update-exam',[ExamController::class,'update']);
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
    ////grades
    Route::post('/create-grade',[GradeController::class,'store']);
    Route::post('/delete-grade',[GradeController::class,'destroy']);
    Route::post('/update-grade',[GradeController::class,'update']);
    ////grades
    Route::post('/create-fee',[FeeController::class,'store']);
    Route::post('/delete-fee',[FeeController::class,'destroy']);
    Route::post('/update-fee',[FeeController::class,'update']);
    ///student
    Route::post('/create-student',[StudentController::class,'store']);
    Route::post('/class_students',[StudentController::class,'classStudents']);
    Route::post('/reflesh_students',[StudentController::class,'refleshStudents']);
    Route::post('/search_student_index_no',[studentController::class,'getStudentByIndexNo']);
    ///staff
    Route::post('/create-staff',[StaffController::class,'store']);
    ///parent
    Route::post('/create-parent',[ParentController::class,'store']);
    Route::post('/search-parent',[ParentController::class,'show']);

    ///feeepayments  addFeeToStudent /add_deposit_slip
    Route::post('/check_required_fees',[FeePaymentController::class,'checkRequiredFees']);
    Route::post('/add_fee_to_student',[FeePaymentController::class,'addFeeToStudent']);
    Route::post('/add_payment_to_student',[FeePaymentController::class,'addPaymentToStudent']);
    ////deposit slip
    Route::post('/add_deposit_slip',[DepositSlipController::class,'addDepositSlip']);
});


Route::controller(AuthController::class)->group(function(){
    Route::post('login','login');
    //Route::post('logout','logout');
});
