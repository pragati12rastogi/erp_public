<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\State;
use Spatie\Permission\Models\Role;
use App\Custom\Constants;
use Illuminate\Support\Facades\Hash;
use App\Models\InvoiceSetting;
use App\Models\District;
use App\Models\Area;
use Auth;
use DB;
use Mail;
use Validator;
use Image;
use PDF;
use Crypt;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        // create_form
        $roles = Role::where('name','<>',Constants::ROLE_ADMIN)->get();
        
        $states = State::all();
        //listing
        $users = User::whereHas('role',function($query){
            $query->where('name','<>',Constants::ROLE_ADMIN);
        })->get();
        
        return view('user.index',compact('users','roles','states'));

    }

    public function user_list_api(Request $request){
        $search = $request->input('search');
        $serach_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        $s_date = $request->input('startDate');
        $e_date = $request->input('endDate');

        $users = User::with(['role','created_by_user','updated_by_user'])->whereHas('role',function($query){
            $query->where('name','<>',Constants::ROLE_ADMIN);
        });
        
        if(!empty($s_date) && empty($e_date))
        {
            $users->where(function($query) use ($s_date){
                $query->where('created_at','>=',$s_date);
            });                
        }else if(!empty($e_date) && empty($s_date)){
            $users->where(function($query) use ($e_date){
                $query->where('created_at','<=',$e_date);
            });   
        }else if(!empty($e_date) && !empty($s_date)){
            $users->where(function($query) use ($s_date,$e_date){
                $query->whereBetween('created_at',array($s_date,$e_date));
            });   
        }

        if(!empty($serach_value))
        {
            $users->where(function($query) use ($serach_value){
                $query->where('name','LIKE',"%".$serach_value."%")
                ->orWhereHas('role',function($query)use($serach_value){
                    $query->where('name','LIKE',"%".$serach_value."%");
                })
                ->orwhere('firm_name','like',"%".$serach_value."%")
                ->orwhere('email','like',"%".$serach_value."%")
                ->orwhere('mobile','like',"%".$serach_value."%")
                ;
            });                
        }
        
        $count = $users->get()->count();
        $users = $users->offset($offset)->limit($limit);
        if(isset($request->input('order')[0]['column'])){
            $data = ['id','name','id','firm_name','email','mobile','created_at','id','status'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $users->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else{
            $users->orderBy('id','desc');
        }

        $users_data = $users->get()->toArray();
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $users_data; 
        
        return json_encode($array);
 
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $roles = Role::where('name','<>',Constants::ROLE_ADMIN)->get();
        
        $states = State::all();
        return view('user.add',compact('roles','states'));
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
                'role'          => ['required'],
                'name'             => ['required', 'string', 'max:255'],
                'email'            => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'mobile'           => ['required','unique:users'],
                'firm_name'        => ['required'],
                'gst_no'           => ['required'],
                'state_id'         => ['required'],
                'district'         => ['required'],
                'image'            => ['mimes:jpeg,png,jpg,gif']
            ],[
                'role.required'        => 'Role is required',
                'name.required'        => 'Name is required',
                'name.string'          => 'Name field can only accept string',
                'name.max'             => 'Name field max length is 255',
                'email.required'       => 'Email field is required',
                'email.string'         => 'Email field can only accept string',
                'email.max'            => 'Email field max length is 255',
                'email.unique'         => 'Email is already in used',
                'mobile.required'      => 'Mobile field is required',
                'firm_name.required'   => 'Firm Name field is required',
                'gst_no.required'      => 'GST No field is required',
                'state_id.required'    => 'State field is required',
                'district.required'    => 'District field is required',
                'image.mimes'          => 'Image accept only jpeg,png,jpg,gif',
            ]);

            if($validation->fails()){
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.', ';
                    
                }
                return back()->with('error',$message);
            }
            

            $role = Role::where('name',$request->input('role'))->first();
            
            $input['role_id'] = $role->id;
            
            DB::beginTransaction();
            
    
            if ($file = $request->file('image')) {
    
                $optimizeImage = Image::make($file);
                $optimizePath = public_path() . '/uploads/user_profile/';
                $image = time() .'.'. $file->getClientOriginalExtension();
                $optimizeImage->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $optimizeImage->save($optimizePath . $image, 90);
    
                $input['profile'] = $image;
    
            }

            $input['status'] =1;
            $dummy_password = str::random(8);
            $input['password'] = Hash::make($dummy_password);
            $input['created_by'] = Auth::id();
            $users = User::create($input);
            
            // assigning roles and its permission to users
            $users->assignRole($request->input('role'));
            
            /* -------------Creating Invoice Setting--------------- */
            $prefix = $this->random_inv_prefix(5);
            $new_inv_setting = InvoiceSetting::create([
                'user_id' => $users->id,
                'prefix' => $prefix,
                'suffix_number_length' => '001',
                'updated_by' => 0
            ]);

            

        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return back()->with('error','some error occurred'.$ex->getMessage());
        }
        

        $from_email = env('MAIL_FROM_ADDRESS');
        $from_name = env('MAIL_FROM_NAME');

        Mail::send('emails.welcomemail', ['user'=>$input,'password'=>$dummy_password], function($message) use($from_email,$from_name,$input)
        {
            $message->to($input['email'])->from($from_email)->subject('Login Credentails From '.$from_name);
        });

        DB::commit();
        return back()->with('success','User is created successfully');
        
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
        
        $user = User::findOrFail($id);
        $user['edit_url'] = url('users/'.Crypt::encrypt($id));
        $user['role_name'] = $user->role->name;
        $user['p_image'] = '';
        if($user->profile != '' && file_exists(public_path().'/uploads/user_profile/'.$user->profile)){
            $user['p_image'] = url('/uploads/user_profile/'.$user->profile);
        }
        echo json_encode(compact('user'));
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
            $id = Crypt::decrypt($id);
            $input = $request->all();
            
            $validation = Validator::make($input,[
                'role'          => ['required'],
                'name'             => ['required', 'string', 'max:255'],
                // 'email'            => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'mobile'           => ['required','unique:users,mobile,'.$id.',id'],
                'firm_name'        => ['required'],
                'gst_no'           => ['required'],
                'state_id'         => ['required'],
                'district'         => ['required'],
                'image'            => ['mimes:jpeg,png,jpg,gif']
            ],[
                'role.required'       => 'This field is required',
                'name.required'          => 'This field is required',
                'name.string'            => 'This field can only accept string',
                'name.max'               => 'This field max length is 255',
                'email.required'         => 'This field is required',
                'email.string'            => 'This field can only accept string',
                'email.max'               => 'This field max length is 255',
                'mobile.required'         => 'This field is required',
                'firm_name.required'         => 'This field is required',
                'gst_no.required'         => 'This field is required',
                'state_id.required'         => 'This field is required',
                'district.required'         => 'This field is required',
                'image.mimes'            => 'Field accept only jpeg,png,jpg,gif',
            ]);

            if($validation->fails()){
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.', ';
                }
                return back()->with('error',$message);
            }

            $role = Role::where('name',$request->input('role'))->first();
            
            $input['role_id'] = $role->id;
            
            DB::beginTransaction();
            $users = User::findOrFail($id);
    
            if ($file = $request->file('image')) {
                
                if ($users->profile != '' && file_exists(public_path() . '/uploads/user_profile/' . $users->profile)) {
                    unlink(public_path() . '/uploads/user_profile/' . $users->profile);
                }

                $optimizeImage = Image::make($file);
                $optimizePath = public_path() . '/uploads/user_profile/';
                $image = time() .'.'. $file->getClientOriginalExtension();
                $optimizeImage->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $optimizeImage->save($optimizePath . $image, 90);
    
                $input['profile'] = $image;
    
            }
            $input['updated_by'] = Auth::id();
            $users->update($input);
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $users->assignRole($request->input('role'));


        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return back()->with('error','some error occurred'.$ex->getMessage());
        }
        
        DB::commit();
        return back()->with('success','User is updated successfully');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if(!is_admin(Auth::user()->role_id)){
        //     return abort('401', 'Not Aurthorized');
        // }

        $user = User::find($id);

        if ($user->profile != '' && file_exists(public_path() . '/uploads/user_profile/' . $user->profile)) {
            unlink(public_path() . '/uploads/user_profile/' . $user->profile);
        }

        $value = $user->delete();

        if ($value) {
            return back()->with('success','User Has Been Deleted');
        }
    }

    public function status_update($id){
        
        // if(!is_admin(Auth::user()->role_id)){
        //     return abort('401', 'Not Aurthorized');
        // }

        $f = User::findorfail($id);

        if($f->status==1)
        {
            $f->update(['status' => "0"]);
            return back()->with('success',"Status changed to Deactive !");
        }
        else
        {
            $f->update(['status' => "1"]);
            return back()->with("success","Status changed to Active !");
        }
    }


    public function export_table($type){
        $outcolumn = [
            'Id','Role Name','Name','Email','Mobile','Firm Name','GST Number','State Name', 'District', 'Area', 'Address','Bank Name','Name On Passbook','IFSC','Account No','PAN'
        ];

        $column =['users.id','roles.name as role_name','users.name','users.email','users.mobile','users.firm_name','users.gst_no','states.name as state_name','districts.name as district_name','areas.name as area_name','users.address','users.bank_name','users.name_on_passbook','ifsc','account_no','pan_no'];
        
        $users = User::whereHas('role',function($query){
            $query->where('name','<>',Constants::ROLE_ADMIN);
        })->leftjoin('roles','roles.id','users.role_id')
        ->leftjoin('states','states.id','users.state_id')
        ->leftjoin('districts','districts.id','users.district')
        ->leftjoin('areas','areas.id','users.area_id')
        ->select($column)->get()->toArray();

        if($type == 'excel'){

            $file_name = 'Users_excel_'.strtotime('now').'.csv';
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=".$file_name);
            
            $fp = fopen('php://output', 'w');
            
            if ($fp) {
                
                $tmpArray = $outcolumn;
                fputcsv($fp, $tmpArray);
                
                
                foreach($users as $key => $data) {
                    
                    $tmpArray = $data;
                            
                    fputcsv($fp, $tmpArray);
                }
                
            }   
            
            fclose($fp);

        }else if($type == 'pdf'){

            $data = array(
                "table_data" => $users
            );
            $pdfFilePath = "users_pdf_".strtotime('now').".pdf";
            $pdf = PDF::loadView('pdf.usertemplate',$data);
            return $pdf->stream($pdfFilePath);
            
        }
    }

    public function user_profile_update(){
        $user = User::findOrFail(Auth::id());

        $roles = Role::get();
        $states = State::all();
        $userRole = $user->roles->pluck('name','name')->first();
        $districts = District::where('state_id',$user->state_id)->get();
        $areas = Area::where('district_id',$user->district)->get();
        return view('user.user-profile',compact('user','roles','states','userRole','districts','areas'));
    }

    public function update_user_password(Request $request){
        try {
            $user_id = Auth::id();
            $this->validate($request,[
                'current_password'=>'required',
                'new_password'=>'required|min:8',
                'password_confirmation'=>'required|same:new_password'
            ],[
                'current_password.required'=> 'This is required.',
                'new_password.required'=> 'This is required.' ,  
                'new_password.min'=> 'Minimum length is 8 character.' ,  
                'password_confirmation.required'=> 'This is required.',
                'password_confirmation.same'=> 'Password not matched.'   
            ]);

            DB::beginTransaction();
            
            $current_pass = $request->input('current_password');
            $new_pass = $request->input('new_password');
            $confirm_pass = $request->input('password_confirmation');

            $user = User::where('id',$user_id)->first();
            if(!Hash::check($current_pass,$user['password'])){
            
                DB::rollback();
                return back()->with('error','The specified password does not match your old password');
            
            }else{
                
                $update = User::where('id',$user_id)->update([
                    'password'=>Hash::make($confirm_pass),
                ]);
            
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            return back()->with('error','some error occurred'.$ex->getMessage());
        }
        if($update){
            DB::commit();
            return back()->with('success','Password Updated Successfully');
        }
    }

    public function random_inv_prefix($length = 5){
        $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMONPQRSTUVWXYZ';
        
        $rand_str = substr(str_shuffle($string),1,$length);
        $check_prefix = InvoiceSetting::where('prefix',$rand_str)->first();
        /* -------------- check generated prefix -------------- */
        if(empty($check_prefix)){
            return $rand_str;
        }else{
            return $this->random_inv_prefix($length);
        }
        
    }
}
