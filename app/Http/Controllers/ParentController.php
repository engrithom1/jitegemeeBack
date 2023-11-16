<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use DB;
use App\Models\User;
use App\Models\Gender;
use App\Models\Parento;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return DB::table('parents')
            ->orderBy('parents.id','desc')
            ->join('genders', 'genders.id', '=', 'parents.gender')
            ->select('genders.gender','parents.id','parents.first_name','parents.last_name','parents.middle_name','parents.phone','parents.home_address')
            ->get();
            //return Parento::orderBy('parents.id','desc')->get();
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
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'nationality' => 'required',
            'gender' => 'required',
            'phone' => ['required', 'string', 'max:255', 'unique:parents'],
            'home_address' => 'required',
            'role_id' => 'required',
            'occupation' => 'required',
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

            $role_id = $request->role_id;

            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            if($request->hasFile('photo')){
                //return 12345;
                $nameWithExtension = $request->file('photo')->getClientOriginalName();
    
                $imgname = pathinfo($nameWithExtension, PATHINFO_FILENAME);
    
                $extension = $request->file('photo')->getClientOriginalExtension();
    
                $nameToStore = $imgname.'_'.time().'.'.$extension;
    
                request()->photo->move(public_path('/imagies/'), $nameToStore);
    
     //$path = $request->file('photo')->storeAs('/public/img/logos/',$nameToStore);
            }else{
                //return 65432;
                $nameToStore = 'man.png';
            }
            
            $parent = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'middle_name' => $request->middle_name,
                'nationality' => $request->nationality,
                'occupation' => $request->occupation,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'home_address' => $request->home_address,
                'photo' => $nameToStore,
                'user_id' => $request->user_id,
                'email' => $request->email,
            ];

            Parento::create($parent);
            $parents = Parento::orderBy('parents.id','desc')->get();

            $response = [
                'success' => true,
                'message' => $request->first_name." ".$request->last_name." added Successfuly",
                'parents'   => $parents
            ];

            return response()->json($response, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'search_parent' => 'required',
            
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);

        }else{

            $search = $request->search_parent;

            $parents = Parento::where('first_name','like',"%{$search}%")->orWhere('last_name','like',"%{$search}%")->orWhere('middle_name','like',"%{$search}%")->orWhere('phone','like',"%{$search}%")->get();

            $response = [
                'success' => true,
                'parents' => $parents
            ];

            return response()->json($response, 200);
        }
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
