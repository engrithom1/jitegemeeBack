<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Department::all();
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
        'department' => ['required', 'string', 'max:255', 'unique:departments'],
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
            $department = $request->department;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'department' => $department,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'Create the department by the name of '.$department
            ];

            Department::create($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $departments = Department::all();

            $response = [
                'success' => true,
                'message' => "department added Successfuly",
                'departments'   => $departments
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
        'department' => ['required', 'string', 'max:255', 'unique:departments'],
       ]);

       if($validator->fails()){
        $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
        return response()->json($response, 200);

        }else{

            $user_id = $request->user_id;
            $role_id = $request->user_id;
            $department = $request->department;
            $department_id = $request->department_id;
            $og_department = $request->og_department;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'department' => $department,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'department edited from the name of '.$og_department.' to '.$department
            ];

            Department::where(['id'=>$department_id])->update($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $departments = Department::all();

            $response = [
                'success' => true,
                'message' => "department edited Successfuly",
                'departments'   => $departments
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
            $department_id = $request->department_id;
            $department = $request->department;
        
            if($role_id < 4){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $log = [
                'user_id' => $user_id,
                'log' => 'Delete the department by the name of '.$department.', its id was '.$department_id,
            ];
    
            Department::where(['id' => $department_id])->delete();
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $departments = Department::all();
    
            $response = [
                'success' => true,
                'message' => "Department deleted Successfuly",
                'departments'   => $departments
            ];
    
            return response()->json($response, 200);
        
        } catch (\Throwable $th) {
            //return $th;
            return response()->json(['success' => false,
                'message' => ['Database or Server Errors']], 200);
        }
    }
}
