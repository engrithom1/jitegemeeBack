<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\Staff;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return Staff::orderBy('staff.role_id','desc')
            ->join('users','staff.index_no', '=', 'users.index_no')
            ->join('roles','staff.role_id', '=', 'roles.id')
            ->join('departments','staff.department_id', '=', 'departments.id')
            ->select('staff.email','staff.home_address','staff.photo','staff.phone','staff.gender','staff.initial','staff.last_name','staff.middle_name','staff.first_name','departments.department','roles.color','roles.role','users.username','users.id')
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
    public function store(Request $request)
    {
        ///validatio goes here
        $validator = Validator::make($request->all(),[
            'username' =>  ['required', 'string', 'max:255', 'unique:users'],
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'initial' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'home_address' => 'required',
            'role' => 'required',
            'department' => 'required',
            'photo' => 'required',
            'user_id' => 'required',
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }else{
            //return $request->all();

            $password = bcrypt($request->phone);
            $index_no = random_int(10000,99999);

            $staff = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'initial' => $request->initial,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'home_address' => $request->home_address,
                'role_id' => $request->role,
                'department_id' => $request->department,
                'photo' => $request->photo,
                'user_id' => $request->user_id,
                'index_no' => $index_no,
                'email' => $request->username,
            ];

            $user = [
                'username' => $request->username,
                'role_id' => $request->role,
                'type' => "staff",
                'index_no' => $index_no,
                'password' => $password,
            ];

            Staff::create($staff);
            User::create($user);

            $response = [
                'success' => true,
                'message' => $request->initial." ".$request->first_name." ".$request->last_name." added Successfuly"
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
