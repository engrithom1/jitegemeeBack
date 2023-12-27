<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Subject;
use Illuminate\Http\Request;
use DB;

class SubjectController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function alevelSubjects()
    {
        return Subject::where(['level_id' => 8])->get();
    }
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function olevelSubjects()
    {
        return Subject::where(['level_id' => 7])->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Subject::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexLevel()
    {
        return DB::table('subjects')
                 ->orderBy('level_id','asc')
                 ->join('levels','levels.id','subjects.level_id')
                 ->select('levels.level','subjects.subject','subjects.code','subjects.id')
                 ->get();
    }

   
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function classSubjects(Request $request)
    {
        $subs_idz = $request->subs_idz;

        $subs_idz = explode(',', $subs_idz);

        return Subject::whereIn('id' ,$subs_idz)
                      ->select('id','subject','code')->get();
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
        'subject' => ['required', 'string', 'max:255'],
        'code' => ['required', 'string', 'max:255', 'unique:subjects'],
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
            $level_id = $request->level_id;
            $subject = $request->subject;
            $code = $request->code;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $subs = Subject::where(['subject' => $subject, 'level_id' => $level_id])->get();

            if(count($subs) === 0){

            $data = [
                'user_id' => $user_id,
                'level_id' => $level_id,
                'subject' => $subject,
                'code' => $code,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'Create the subject by the name of '.$subject
            ];

            Subject::create($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $subjects = DB::table('subjects')
                        ->orderBy('level_id','asc')
                        ->join('levels','levels.id','subjects.level_id')
                        ->select('levels.level','subjects.subject','subjects.code','subjects.id')
                        ->get();

            $response = [
                'success' => true,
                'message' => "subject added Successfuly",
                'subjects'   => $subjects
            ];

            return response()->json($response, 200);
            }else{
                $response = [
                    'success' => false,
                    'message' => "Subject for a particular Level is Existing",
                ];

                return response()->json($response, 200);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $subject_id = $request->subject_id;
         ///validatio goes here
       $validator = Validator::make($request->all(),[
        'subject' => 'required', 'string', 'max:255', 'unique:subjects,'.$subject_id,
        'code' => 'required', 'string', 'max:255', 'unique:subjects,'.$subject_id,
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
            $subject = $request->subject;
            $code = $request->code;
            $og_subject = $request->og_subject;
            
            if($role_id < 3){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $data = [
                'user_id' => $user_id,
                'subject' => $subject,
                'code' => $code,
            ];

            $log = [
                'user_id' => $user_id,
                'log' => 'subject edited from the name of '.$og_subject.' to '.$subject
            ];

            Subject::where(['id'=>$subject_id])->update($data);
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $subjects = DB::table('subjects')
                        ->orderBy('level_id','asc')
                        ->join('levels','levels.id','subjects.level_id')
                        ->select('levels.level','subjects.subject','subjects.code','subjects.id')
                        ->get();

            $response = [
                'success' => true,
                'message' => "Subject edited Successfuly",
                'subjects'   => $subjects
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
            $subject_id = $request->subject_id;
            $subject = $request->subject;
        
            if($role_id < 4){
                return response()->json(['success' => false,
                'message' => ['You not allowed to make this action']], 200); 
            }

            $log = [
                'user_id' => $user_id,
                'log' => 'Delete the subject by the name of '.$subject.', its id was '.$subject_id,
            ];
    
            Subject::where(['id' => $subject_id])->delete();
            app('App\Http\Controllers\LogController')->storeLogs($log);

            $subjects = DB::table('subjects')
                        ->orderBy('level_id','asc')
                        ->join('levels','levels.id','subjects.level_id')
                        ->select('levels.level','subjects.subject','subjects.code','subjects.id')
                        ->get();
    
            $response = [
                'success' => true,
                'message' => "Subject deleted Successfuly",
                'subjects'   => $subjects
            ];
    
            return response()->json($response, 200);
        
        } catch (\Throwable $th) {
            //return $th;
            return response()->json(['success' => false,
                'message' => ['Database or Server Errors']], 200);
        }
    }
}
