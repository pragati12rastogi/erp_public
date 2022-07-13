<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holiday;
use DB;
use Validator;
use Auth;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $holidays = Holiday::all();
        return view('holiday.index',compact('holidays'));
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
        try{
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
            $holiday = new Holiday();
            $input['created_by'] = Auth::user()->id;
            $holiday->create($input);
            

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','some error occurred'.$th->getMessage());
        }

        DB::commit();
        return back()->with("success", "Holiday Has Been Added !");
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
        $holiday = Holiday::findOrFail($id);
        echo json_encode(compact('holiday')); 
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
            $holiday = Holiday::findOrFail($id);
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
        return back()->with("success", "Holiday Has Been Updated !");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Holiday::findOrFail($id);
        $cat->delete();
        return back()->with('success','Holiday has been deleted !');
    }
}
