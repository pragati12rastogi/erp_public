<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\ContractTypes;
use App\Models\User;
use App\Models\ContractRenewal;
use Auth;
use DB;
use Validator;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $users = User::get();
        $types = ContractTypes::get();
        $contracts = Contract::with('customer','created_by_user')->get();
        return view('contract.index',compact('contracts','users','types'));
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
                'user_id'=> 'required',
                'subject'=> 'required',
                'start_date'=> 'required',
                'end_date' => 'required'
            ],[
                
                'user_id.required' => 'Customer is required',
                'subject.required' => 'Subject is required',
                'start_date.required' => 'Start Date is required',
                'end_date.required' => 'End Date is required'
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

            if(!empty($input['end_date'])){
                if(strtotime($input['start_date'])>strtotime($input['end_date'])){
                    return back()->with('Start Date cannot be greater than End Date');
                }
            }
            
            $input['created_by'] = Auth::id();

            $contract = new Contract;
            $contract_id = $contract->create($input);

            $contract_renew = new ContractRenewal;
            $input['contract_id'] = $contract_id->id;
            $input['renewal'] = 1;
            $contract_renew->create($input);

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
        $contract = Contract::findOrFail($id);
        echo json_encode(compact('contract'));
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
                'user_id'=> 'required',
                'subject'=> 'required',
                'start_date'=> 'required',
                'end_date' => 'required'
            ],[
                
                'user_id.required' => 'Customer is required',
                'subject.required' => 'Subject is required',
                'start_date.required' => 'Start Date is required',
                'end_date.required' => 'End Date is required'
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

            $contract = Contract::find($id);

            if(!empty($input['end_date'])){
                if(strtotime($input['start_date'])>strtotime($input['end_date'])){
                    return back()->with('Start Date cannot be greater than End Date');
                }
            }

            $contract->update($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Contract is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = Contract::find($id);

        $value = $type->delete();
        if ($value) {
            return back()->with('success','Contract is deleted successfully.');
        }
    }

    public function renew_contract($contract_id){
        $contract = Contract::findOrFail($contract_id);
        $contract_renewal = ContractRenewal::select('contract_renewals.*',DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d %H:%i:%s") as new_created_at'))->with(['contract','created_by_user'])->where('contract_id',$contract_id)->where('renewal',0)->get()->toArray();
        echo json_encode(compact('contract','contract_renewal'));
    }

    public function renew_contract_store(Request $request,$contract_id){
        try {
            $input = $request->all();
            $validation = Validator::make($input,[
                'start_date'=> 'required',
                'end_date'=> 'required',
                'value'=> 'required'
            ],[
                'value.required' => 'Value is required',
                'start_date.required' => 'Start Date is required',
                'end_date.required' => 'End Date is required'
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

            if(!empty($input['end_date'])){
                if(strtotime($input['start_date'])>strtotime($input['end_date'])){
                    return back()->with('Start Date cannot be greater than End Date');
                }
            }
            
            $contract = Contract::find($contract_id);
            $contract->update($input);

            $input['contract_id'] = $contract_id;
            $input['created_by'] = Auth::id();
            $contract_renew = new ContractRenewal;
            $contract_renew->create($input);

            

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Contract is renewed successfully');
    }

    public function delete_renewal_contract($renewal_id){
        $delete_renewal = ContractRenewal::findOrFail($renewal_id);
        
        $contract_id = $delete_renewal->contract_id;
        $delete_renewal->delete();

        $renewal = ContractRenewal::where('contract_id',$contract_id)->orderBy('id','desc')->first();
        $contract = Contract::find($contract_id);
        $contract->update([
            'start_date'=>$renewal['start_date'],
            'end_date'=>$renewal['end_date'],
            'value'=>$renewal['value']
        ]);

        return back()->with('success','Renewal contract deleted successfully');
    }
}
