<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Staff;
use App\Models\Student;
use DB;

class AuthController extends Controller
{

    public function login(Request $request){

        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){
            $user = $request->user();

            $index_no = auth::user()->index_no;
            $type = auth::user()->type;
            $status = auth::user()->status;

            if($status == "active"){

                if($type == "staff"){

                    $uza = Staff::where('staff.index_no',$index_no)
                    ->join('users','staff.index_no', '=', 'users.index_no')
                    ->select('staff.photo','users.username','users.id','users.role_id')
                    ->get();

                    $data['user'] = $uza[0];
                }

                if($type == "student"){

                    $uza = Student::where('students.index_no',$index_no)
                    ->join('users','students.index_no', '=', 'users.index_no')
                    ->select('students.photo','users.username','users.id','users.role_id')
                    ->get();

                    $data['user'] = $uza[0];
                }
        
                    $data['token'] = $user->createToken('MyApp')->plainTextToken;
        
                    $response = [
                        'success' => true,
                        'data' => $data, 
                        'message' => "Loged Successfuly"
                    ];
        
                    return response()->json($response, 200);
            }else{
                $response = [
                    'success' => false,
                    'message' => "Your not allowed to Login (contact admin)"
                ];
                return response()->json($response, 200);
            }
        }else{
            $response = [
                'success' => false,
                'message' => "Incorrect Username or Password"
            ];

            return response()->json($response, 200);
        }
    }

    public function logout(Request $request){

        $id = $request->id;

        try {

            DB::table('personal_access_tokens')
                   ->where('tokenable_id',$id)
                   ->delete();
            return [
                'success' => true,
            ];
            
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'success' => false,
            ];
        }
    }
    
}
