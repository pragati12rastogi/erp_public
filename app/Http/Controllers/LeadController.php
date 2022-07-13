<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadSources;
use App\Models\LeadStatus;
use App\Models\User;
use App\Custom\Constants;
use Rap2hpoutre\FastExcel\FastExcel;
use Validator;
use DB;
use Auth;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(is_admin(Auth::user()->role_id)){
            $leads = Lead::with(['assigned_user','status','source'])->where('cust_status',0)->get();
        }else{
            $leads = Lead::where('cust_status',0)->where('assigned_to',Auth::id())->get();
        }
        $statuses = LeadStatus::latest('id')->get();
        $sources = LeadSources::latest('id')->get();
        $users = User::whereHas('role',function($query){
            $query->where('name','<>',Constants::ROLE_ADMIN);
        })->get();
        return view('leads.index',compact('leads','statuses','sources','users'));
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
                'name' => 'required',
                'email' => 'required|email|unique:leads',
                'status' => 'required',
                'source' => 'required',
                'phonenumber' => 'required|between:10,14',
                
            ],[
                'name.required' => 'Name is required',
                'email.required'=> 'Email is required',
                'status.required' => 'Status is required',
                'source.required' => 'Source is required',
                'phonenumber.required' => 'Phone is required',
               
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
            $event = Lead::create($input);
            
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Lead is created successfully');
        
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
        $get_lead = Lead::findOrFail($id);
        echo json_encode(compact('get_lead'));
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
            $lead  = Lead::findOrFail($id);

            $validation = Validator::make($input,[
                'name' => 'required',
                'email' => 'required|email|unique:leads,email,'.$id.',id',
                'status' => 'required',
                'source' => 'required',
                'phonenumber' => 'required|between:10,14',
                
            ],[
                'name.required' => 'Name is required',
                'email.required'=> 'Email is required',
                'status.required' => 'Status is required',
                'source.required' => 'Source is required',
                'phonenumber.required' => 'Phone is required',
               
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
            
            $lead->update($input);
            
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Lead is created successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
        return back()->with('success','Lead is deleted successfully');
    }

    public function lead_import(){
        return view('leads.import');
    }

    public function lead_import_db(Request $request){
        $validator = Validator::make(
            [
                'file' => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:xlsx,xls,csv',
            ]

        );

        if ($validator->fails()) {
            return back()->withErrors('Invalid file !');
        }

        if (!$request->has('file')) {
            return back()->with('warning','Please choose a file !');
        }

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);

        $leadfile = (new FastExcel)->import($request->file('file'));
        
        $error = 0;
        DB::beginTransaction();
        if(count($leadfile)>0){
            foreach($leadfile as $key => $line){

                $rowno = $key + 1;
                $leadname = trim($line['name']);
                $status = trim($line['status']);

                $status_id = LeadStatus::where("name",$status)->first();

                if (!isset($status_id)) {
                    $error++;
                    DB::rollback();
                    return back()->with('error',"Invalid Status name at Row no $rowno Status not found ! Please create it and than try to import this file again !");
                    break;
                }

                $source = trim($line['source']);
                $source_id = LeadSources::where('name',$source)->first();

                if (!isset($source_id)) {

                    $error++;
                    DB::rollback();
                    return back()->with('error',"Invalid Source name at Row no $rowno Source not found ! Please create it and than try to import this file again !");
                    break;
                }

                $assigned_to = trim($line['assigned_to']);
                
                $assigned_to_id = User::where('email',$assigned_to)->first();

                if (!isset($assigned_to_id)) {

                    $error++;
                    DB::rollback();
                    return back()->with('error',"Invalid Assigned To email at Row no $rowno Assigned To not found ! Please create it and than try to import this file again !");
                    break;
                }

                if(empty($line['email'])){
                    $error++;
                    DB::rollback();

                    notify()->error("Email is empty at Row no $rowno ! Please enter Email !");

                    return back();
                    break;
                }

                if(empty($line['mobile'])){
                    $error++;
                    DB::rollback();

                    notify()->error("Mobile is empty at Row no $rowno ! Please enter Mobile !");

                    return back();
                    break;
                }
                
                $prod = Lead::create([
                    "name"                 => $leadname,
                    "source"               => $source_id->id,
                    "status"               => $status_id->id,
                    "assigned_to"          => $assigned_to_id->id,
                    "email"                => $line['email'],
                    "phonenumber"          => $line['mobile'],
                    "description"          => $line['description'],
                    'address'              => $line['address'],
                    'city'                 => $line['city'],
                    "state"                => $line['state'],
                    "country"              => $line['country'],
                    "lead_value"           => $line['lead_value'],
                    "company"              => $line['company'],
                    "pincode"              => $line['zipcode']
                    
                ]);


                if($error == 0){
                    
                    DB::commit();
                    return back()->with('success','Leads Imported Successfully !'. $leadfile->count() . ' Imported !');
                }

            }    
        }else {

            $error++;
            DB::rollback();
            return back()->with('warning','Your excel file is empty !');
        }
    }
}
