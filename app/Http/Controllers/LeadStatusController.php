<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadStatus;
use Validator;
use DB;
use Auth;

class LeadStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = LeadStatus::latest('id')->get();
        return view('lead_status.index',compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $input = $request->all();
            $validation = Validator::make($input,[
                'name'=>'required|unique:lead_status',
                
            ],[
                'name.required'=>'Name is required',
                'name.unique'=>'Status name is already existing',
            ]);

            if($validation->fails()){
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.' ';
                }
                
                return back()->with('error',$message);
            }

            DB::beginTransaction();
            $status = new LeadStatus();

            $status->create($input);


        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','some error occurred'.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Status created successfully.');
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
        $status = LeadStatus::findOrFail($id);
        echo json_encode(compact('status'));
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
        try {

            $input = $request->all();
            $validation = Validator::make($input,[
                'name'=>'required|unique:lead_status,name,'.$id.',id',
                
            ],[
                'name.required'=>'Name is required',
                'name.unique'=>'Status name is already existing',
            ]);

            if($validation->fails()){
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.' ';
                }
                
                return back()->with('error',$message);
            }

            DB::beginTransaction();
            $status = LeadStatus::findOrFail($id);

            $status->update($input);


        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','some error occurred'.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = LeadStatus::findOrFail($id);
        $value = $status->delete();
        if ($value) {
            return back()->with('success','Status Has Been Deleted.');
        }
    }
}
