<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerContact;
use App\Models\Customer;
use Validator;
use DB;
use Auth;

class CustomerContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($customer_id)
    {
        $contacts = CustomerContact::with('created_by_user')->where('customer_id',$customer_id)->get();
        return view('customer_contact.index',compact('contacts','customer_id'));
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
    public function store($customer_id,Request $request)
    {
        try {

            $input = $request->all();
           
            $validation = Validator::make($input,[
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|between:10,14',
                
            ],[
                'name.required' => 'Name is required',
                'email.required'=> 'Email is required',
                'phone.required' => 'Phone is required',
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

            if( isset($input['is_primary']) && $input['is_primary'] == 1 ){
                $cont = CustomerContact::where('customer_id',$customer_id)->where('is_primary',1)->update(['is_primary'=>0]);
            }

            $input['customer_id'] = $customer_id;
            $input['created_by'] = Auth::id();
            $event = CustomerContact::create($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Customer Contact is created successfully');
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
    public function edit($customer_id,$id)
    {
        $contact = CustomerContact::findOrFail($id);
        echo json_encode(compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$customer_id, $id)
    {
        try {

            $input = $request->all();
           
            $validation = Validator::make($input,[
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|between:10,14',
                
            ],[
                'name.required' => 'Name is required',
                'email.required'=> 'Email is required',
                'phone.required' => 'Phone is required',
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

            if( isset($input['is_primary']) && $input['is_primary'] == 1 ){
                $cont = CustomerContact::where('customer_id',$customer_id)->where('is_primary',1)->update(['is_primary'=>0]);
            }

            $event = CustomerContact::findOrFail($id)->update($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Customer Contact is updated successfully');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($customer_id,$id)
    {
        $customer = CustomerContact::find($id);
        $value = $customer->delete();
        
        if ($value) {
            return back()->with('success','Customer is deleted successfully.');
        }
    }
}
