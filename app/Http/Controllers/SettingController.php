<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\InvoiceSetting;
use App\Models\BillingSetting;
use App\Models\GeneralSetting;
use Auth;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Exception;
use Twilio\Rest\Client;
use Image;
use Illuminate\Support\Facades\Response;

class SettingController extends Controller
{
    public function invoice_master(){

        $invoice_setting = InvoiceSetting::where('user_id',Auth::id())->first();
        $users = InvoiceSetting::whereHas('user')->get();
        return view('setting.invoice_master',compact('invoice_setting','users'));
    }

    public function save_invoice_master(Request $request){
        try {
            
            $input = $request->all();
            $this->validate($request,[
                'prefix' => 'required|unique:invoice_settings,prefix,'.$input['user_id'].',user_id',
                'suffix_number_length' => 'required|numeric|gt:0'
            ],[
                'prefix.required' => 'This is required',
                'prefix.unique' => 'Prefix is already used',
                'suffix_number_length.required' => 'This is required',
                'suffix_number_length.numeric' => 'This field accept number only',
                'suffix_number_length.gt' => 'Enter value greater than 0',
            ]);
            
            DB::beginTransaction();
            
            
            $input['updated_by'] = Auth::id();
            $inv = InvoiceSetting::where('user_id',$input['user_id'])->first();
            $inv->update($input);
            
            
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return back()->with('Some Error Occurred : '.$ex->getMessage());
        }

        DB::commit();
        return back()->with('success','Invoice Setting is updated');
    }


    public function billing_master(){
        $billing_setting = BillingSetting::first();
        return view('setting.billing_master',compact('billing_setting'));
    }

    public function save_billing_master(Request $request){
        try {
            $this->validate($request,[
                'details' => 'required',
            ],[
                'details.required' => 'This is required',
            ]);
            
            DB::beginTransaction();
            $bill = BillingSetting::first();
            $input = $request->all();
            
            if(!empty($bill)){
                // update
                $bill->update($input);
            }else{
                // insert
                $bill_insert = new BillingSetting();
                $bill_insert->create($input);
                
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return back()->with('Some Error Occurred : '.$ex->getMessage());
        }
        DB::commit();
        return back()->with('success','Billing Address is updated');
    }

    public function email_master(){
        $env_files = ['MAIL_FROM_NAME' => env('MAIL_FROM_NAME') , 'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS') , 'MAIL_MAILER' => env('MAIL_MAILER') , 'MAIL_HOST' => env('MAIL_HOST') , 'MAIL_PORT' => env('MAIL_PORT') , 'MAIL_USERNAME' => env('MAIL_USERNAME') , 'MAIL_PASSWORD' => env('MAIL_PASSWORD') , 'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION') ,

        ];
        return view('setting.mail_master', compact('env_files'));
    }

    public function email_master_db(Request $request){
        $env_keys_save =  DotenvEditor::setKeys([

            'MAIL_FROM_NAME' => $request->MAIL_FROM_NAME, 
            'MAIL_MAILER' => $request->MAIL_MAILER, 
            'MAIL_HOST' => $request->MAIL_HOST, 
            'MAIL_PORT' => $request->MAIL_PORT, 
            'MAIL_USERNAME' => $request->MAIL_USERNAME, 
            'MAIL_FROM_ADDRESS' => preg_replace('/\s+/', '', $request->MAIL_FROM_ADDRESS) , 
            'MAIL_PASSWORD' => $request->MAIL_PASSWORD, 
            'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION

        ]);

        $test = $env_keys_save->save();

        
        return back()->with('success','Mail settings saved !');

    }

    public function sms_master(){
        return view('setting.sms_master');
    }

    public function sms_master_db(Request $request){
        $env_keys_save = DotenvEditor::setKeys([
            'TWILIO_SID' => $request->TWILIO_SID,
            'TWILIO_AUTH_TOKEN' => $request->TWILIO_AUTH_TOKEN,
            'TWILIO_NUMBER' => $request->TWILIO_NUMBER
        ]);

        $env_keys_save->save();

        // $receiverNumber = "+918090565200";
        // $message = "This is testing from ItSolutionStuff.com";
  
        // try {
  
        //     $account_sid = env("TWILIO_SID");
        //     $auth_token = env("TWILIO_AUTH_TOKEN");
        //     $twilio_number = env("TWILIO_NUMBER");
  
        //     $client = new Client($account_sid, $auth_token);
        //     $client->messages->create($receiverNumber, [
        //         'from' => $twilio_number, 
        //         'body' => $message]
        //     );
  
        //     dd('SMS Sent Successfully.');
  
        // } catch (Exception $e) {
        //     dd("Error: ". $e->getMessage());
        // }

        return back()->with("success","SMS settings updated !");
    }

    public function general_master(){
        $setting = GeneralSetting::first();
        return view('setting.general_master',compact('setting'));
    }

    public function general_master_db(Request $request){
        $this->validate($request,[
            'project_name'=>'required',
            'APP_URL'=>'required',
            
        ],[
            'project_name.required'=> 'This is required.',
            'APP_URL.required'=> 'This is required.',
             
        ]);

        $input = $request->all();
        $general = GeneralSetting::first();

            if ($request->logo) {

                $image = $request->file('logo');
                $input['logo'] = 'logo_'.uniqid() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/general');
                $img = Image::make($image->path());
    
                if ($general->logo != '' && file_exists(public_path() . '/images/general/' . $general->logo)) {
                    unlink(public_path() . '/images/general/' . $general->logo);
                }
    
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });
    
                $img->save($destinationPath . '/' . $input['logo']);
    
            }
    
            if ($file = $request->file('favicon')) {
    
                $image = $request->file('favicon');
                $input['favicon'] = 'favicon_'.uniqid() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/general');
                $img = Image::make($image->path());
    
                if ($general->favicon != null) {
    
                    if ($general->favicon != '' && file_exists(public_path() . '/images/general/' . $general->favicon)) {
                        unlink(public_path() . '/images/general/' . $general->favicon);
                    }
    
                }
    
                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });
    
                $img->save($destinationPath . '/' . $input['favicon']);
    
            }

            $general->update($input);

            $env_keys_save = DotenvEditor::setKeys([
                'APP_NAME' => $request->project_name,
                'APP_URL' => $request->APP_URL,
                'ONESIGNAL_APP_ID' => $request->ONESIGNAL_APP_ID,
                'ONESIGNAL_REST_API_KEY'=> $request->ONESIGNAL_REST_API_KEY,
                'FACEBOOK_APP_ID' => $request->FACEBOOK_APP_ID,
                'FACEBOOK_APP_SECRET' => $request->FACEBOOK_APP_SECRET,
                'FACEBOOK_GRAPH_VERSION' => $request->FACEBOOK_GRAPH_VERSION
            ]);

            $env_keys_save->save();
            return back()->with("success","General Setting Has Been Updated !");
    }

    public function getUserInvoiceSetting(Request $request){
        
        $invoice_setting = InvoiceSetting::where('user_id',$request->user_id)->first();
        if(!empty($invoice_setting)){
            return Response::json(['status'=>'success','data'=>$invoice_setting]);
        }else{
            return Response::json(['status'=>'error']);
        }
    }

    public function paymentGatewayMaster(){
        return view('setting.payment');
    }

    public function paymentGatewayMasterDb(Request $request){
        $input = $request->all();
        if(isset($input['RAZORPAY_ACTIVE'])){
            $this->validate($request,[
                'RAZORPAY_KEY'=>'required',
                'RAZORPAY_SECRET'=>'required'
            ],[
                'RAZORPAY_KEY.required'=>'This field is required', 
                'RAZORPAY_SECRET.required'=>'This field is required' 
            ]);
        }
        

        $env_keys_save = DotenvEditor::setKeys([

            'RAZORPAY_KEY' => $input['RAZORPAY_KEY'], 
            'RAZORPAY_SECRET' => $input['RAZORPAY_SECRET'],
            'RAZORPAY_ACTIVE' => isset($input['RAZORPAY_ACTIVE']) ? 1 : 0

        ]);

        $env_keys_save->save();

        return back()->with('success','Razorpay settings has been updated !');
    }

    

}
