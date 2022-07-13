<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use App\Models\ExpenseMaster;

class ExpenseMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expense_masters = ExpenseMaster::all();
        return view('expense_master.index',compact('expense_masters'));
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
                'name' =>'required|unique:expense_masters'
            ],[
                'name.required'=>'Name is required',
                'name.unique'=>'Name is already present',
                
            ]);
            if($validation->fails()){
                
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.', ';
                    
                }
                
                return back()->with('error',$message);
                
            }
            DB::beginTransaction();
            $exp = new ExpenseMaster();
            $input['created_by'] = Auth::id();
            $input['name'] = ucwords($input['name']);
            $exp->create($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Expense Master is created successfully');
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
        $exp = ExpenseMaster::findOrFail($id);
        echo json_encode(compact('exp'));
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
                'name' =>['required','unique:expense_masters,name,'.$id.',id']
            ],[
                'name.required'=>'Name is required',
                'name.unique'=>'Name is already present',
                
            ]);
            if($validation->fails()){
                
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.', ';
                    
                }
                
                return back()->with('error',$message);
                
            }
            DB::beginTransaction();
            $exp = ExpenseMaster::findOrFail($id);
            $input['updated_by'] = Auth::id();
            $input['name'] = ucwords($input['name']);
            $exp->update($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Expense Master is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exp = ExpenseMaster::find($id);
        $value = $exp->delete();
        if ($value) {
            return back()->with('success','Expense Master is deleted successfully.');
        }
    }
}
