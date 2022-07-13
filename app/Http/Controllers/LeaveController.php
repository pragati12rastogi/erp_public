<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use DB;
use Auth;
use Validator;
use App\Custom\Constants;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $users = User::where('status',1)->get();
        if(is_admin(Auth::user()->role_id)){
            $leaves = Leave::get();
        }else{
            $leaves = Leave::where('user_id',$user_id)->get();
        }
        return view('leave.index',compact('leaves','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $input = $request->all();
            $validation = Validator::make($input,[
                'user_id'=>'required',
                'from_date'=>'required',
                'to_date'=>'required',
                'reason'=>'required',
            ],[
                'user_id.required'=>'Name is required',
                'from_date.required'=>'From Date is required',
                'to_date.required'=>'To Date is required',
                'reason.required'=>'Reason is required'
            ]);

            if($validation->fails()){
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.'. ';
                    
                }
                return back()->with('error',$message);
            }

            DB::beginTransaction();
            $leave = new Leave();
            $users = User::whereHas('role',function($query){
                $query->where('name',Constants::ROLE_ADMIN);
            })->first();

            if($users->id == $input['user_id']){
                $input['approved'] = 1;
            }
            $leave->create($input);
            

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','some error occurred'.$th->getMessage());
        }

        DB::commit();
        return back()->with("success", "Leave Has Been Added !");
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leave = Leave::findOrFail($id);
        echo json_encode(compact('leave')); 
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
        try{
            $holiday = Leave::findOrFail($id);
            $input = $request->all();
            $validation = Validator::make($input,[
                'name'=>'required',
                'date'=>'required',
            ],[
                'name.required'=>'Name is required',
                'date.required'=>'Date is required'
            ]);
            if($validation->fails()){
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.'. ';
                    
                }
                return back()->with('error',$message);
            }
            DB::beginTransaction();
                
            $holiday->update($input);
        
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','some error occurred'.$th->getMessage());
        }

        DB::commit();
        return back()->with("success", "Leave Has Been Updated !");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Leave::findOrFail($id);
        $cat->delete();
        return back()->with('success','Leave has been deleted !');
    }

    public function leave_approved($leave_id){
        $leave = Leave::findOrFail($leave_id);
        $leave->update(['approved'=>1]);
        return back()->with('success','Leave approved !!' );
    }
}
