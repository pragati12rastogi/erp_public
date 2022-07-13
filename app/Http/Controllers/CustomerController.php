<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use DB;
use Auth;
use Validator;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $customers = Customer::with('primary')->get();
        return view('customer.index',compact('customers'));
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
                'name' => 'required',
                'email' => 'required|email',
                'company' => 'required',
                'phone' => 'required|between:10,14',
                
            ],[
                'name.required' => 'Name is required',
                'email.required'=> 'Email is required',
                'company.required' => 'Company is required',
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
            $input['created_by'] = Auth::id();
            $event = Customer::create($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Customer is created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        echo json_encode(compact('customer'));
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
                'name' => 'required',
                'email' => 'required|email',
                'company' => 'required',
                'phone' => 'required|between:10,14',
                
            ],[
                'name.required' => 'Name is required',
                'email.required'=> 'Email is required',
                'company.required' => 'Company is required',
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
            $event = Customer::findOrFail($id);

            $event->update($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Customer is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $value = $customer->delete();
        
        if ($value) {
            return back()->with('success','Customer is deleted successfully.');
        }
    }

    public function convertToCustomer($lead_id,Request $request){
        try {

            $input = $request->all();
           
            $validation = Validator::make($input,[
                'name' => 'required',
                'email' => 'required|email',
                'company' => 'required',
                'phone' => 'required|between:10,14',
                // 'country'=> 'required',
                // 'state' => 'required',
                // 'city' => 'required',
                // 'pincode' => 'required',
            ],[
                'name.required' => 'Name is required',
                'email.required'=> 'Email is required',
                'company.required' => 'Company is required',
                'phone.required' => 'Phone is required',
                // 'country.required' => 'Country is required',
                // 'state.required' => 'State is required',
                // 'city.required' => 'City is required',
                // 'pincode.required' => 'Pincode is required',
                
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
            $input['created_by'] = Auth::id();
            $event = Customer::create($input);
            $lead = Lead::find($lead_id)->update(['cust_status' => 1]);
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Customer is created successfully');
    }

    public function getLeadDetails($lead_id){
        $get_lead = Lead::findOrFail($lead_id);
        
        echo json_encode(compact('get_lead'));
    }
}
