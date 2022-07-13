<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContractTypes;
use DB;
use Auth;
use Validator;

class ContractTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tyypes = ContractTypes::get();
        return view('contract_type.index',compact('tyypes'));
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
                'user_id' => 'required',
                'subject' => 'required',
                'value' => 'required',
                
            ],[
                
                'user_id.required' => 'Customer is required',
                'name.unique' => 'Entered Existing Contract Type'
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
            $type = new ContractTypes();
            
            $type->create($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Contract is created successfully');
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
        $type = ContractTypes::findOrFail($id);
        echo json_encode(compact('type'));
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
                'name'=>'required|unique:contract_types,name,'.$id.',id'
            ],[
                
                'name.required' => 'Contract Type is required',
                'percent.unique' => 'Entered Existing Contract Type'
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
            $type = ContractTypes::findOrFail($id);
            $type->update($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Contract Type is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = ContractTypes::find($id);

        $value = $type->delete();
        if ($value) {
            return back()->with('success','Contract Type is deleted successfully.');
        }
    }
}
