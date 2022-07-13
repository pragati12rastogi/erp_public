<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadSources;
use Validator;
use DB;
use Auth;

class LeadSourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = LeadSources::latest('id')->get();
        return view('lead_source.index',compact('sources'));
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
        try {

            $input = $request->all();
            $validation = Validator::make($input,[
                'name'=>'required|unique:lead_sources',
                
            ],[
                'name.required'=>'Name is required',
                'name.unique'=>'Source name is already existing',
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
            $status = new LeadSources();

            $status->create($input);


        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','some error occurred'.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Source created successfully.');
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
        $source = LeadSources::findOrFail($id);
        echo json_encode(compact('source'));
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
                'name.unique'=>'Source name is already existing',
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
            $status = LeadSources::findOrFail($id);

            $status->update($input);


        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','some error occurred'.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Source updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = LeadSources::findOrFail($id);
        $value = $status->delete();
        if ($value) {
            return back()->with('success','Source Has Been Deleted.');
        }
    }
}
