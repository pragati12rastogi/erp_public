<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FbPageSubscription;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\LeadSources;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Log;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class FacebookController extends Controller
{
  	
    public function webhook_index(Request $request){

        $req = $request->all();
      	
        $challenge = $req['hub_challenge'];
        $verify_token = $req['hub_verify_token'];
      
        if ($verify_token === 'abc123') {
            echo $challenge;
        }
    }
	public function webhook_post(Request $request){
        $input = json_decode(file_get_contents('php://input'), true);
        Log::info(json_encode($input));
       	if(!empty($input)){
            $status = LeadStatus::first();
            $source = LeadSources::where('name','Facebook')->first();
            $entry = $input['entry'];
            $graph_ver = env('FACEBOOK_GRAPH_VERSION');
            foreach($entry as $ind => $details){
                foreach($details['changes'] as $sub_ind => $sub_details){
                    $value_array = $sub_details['value'];
                    $page_access_token = FbPageSubscription::where('subscribed_page_id',$value_array['page_id'])->first();

                    if(!empty($page_access_token)){
                        // curl for form detail
                        $insert_data = [];
                        $fields = array('access_token'  => $page_access_token['sub_access_token'] );

                        $get_form_details_url = 'https://graph.facebook.com/'.$graph_ver.'/'.$value_array['page_id'].'/leadgen_forms';
                            
                        $get_form_result = $this->curl_function($get_form_details_url,$fields);
                        Log::info(json_encode($get_form_result));
                        if ($get_form_result) {
                            $insert_data['description'] = 'Lead Form Name :'. $get_form_result['data'][0]['name'];
                        }

                        // curl for leads detail

                        $get_lead_details_url = 'https://graph.facebook.com/'.$graph_ver.'/'.$value_array['leadgen_id'];
                        $get_lead_result = $this->curl_function($get_lead_details_url,$fields);
						Log::info(json_encode($get_lead_result));
                        if ($get_lead_result) {
                  
                            $field_data = $get_lead_result['field_data'];
                            
                            foreach($field_data as $i => $name_value){
                                $name = $name_value['name'];
                                $value = $name_value['values'][0];
        
                                
                                if($name == 'full_name'){
                                    $insert_data['name'] = $value;
                                }else if($name == "email"){
                                    $insert_data['email'] = $value;
                                }else if($name == "phone_number"){
                                    $insert_data['phonenumber'] = $value;
                                }
       
                            }
       
                        }

                        $insert_data['source'] = $source;
                        $insert_data['status'] = $status;
                        $insert_data['cust_status'] = 1;
                        $insert_data['created_at'] = date('Y-m-d H:i:s');

                        // insert in leads 
                        $insert_lead = Lead::insertGetId($insert_data);
                        
                        if (empty($insert_lead)) {
                            Log::error("Leads Id ".$value_array['leadgen_id']." Not Inserted");
                        }
                    }
                }
            }
        }
    }
  
    public function curl_function($url,$fields){
        $curl = curl_init();
  
        $new_url = $url ."?". http_build_query( $fields );
  
        curl_setopt_array($curl, array(
        CURLOPT_URL => $new_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
  
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
  
  
        if ($error) {
           
            Log::error("cURL Error #:", $error);
            return false;
  
        } else {
  
            return json_decode($response,true);
  
        }
        
        
  
    }

    public function save_fb_page_subscription(Request $request){
        $input = $request->input('page');
        
        $check_sub = FbPageSubscription::where('subscribed_page_id',$input['id'])->first();

        $input_data = [
            'subscribed_page_name'  => $input['name'],
            'sub_access_token'      => $input['access_token'],
            'subscribed_page_id'    => $input['id'],
            'category'              => $input['category'],
            'category_list'         => json_encode($input['category_list'],true),
            'tasks'                 => json_encode($input['tasks'],true),
            'created_at'            => date('Y-m-d H:i:s')
        ];

        if(empty($check_sub)){
            // insert
            $id = FbPageSubscription::insertGetId($input_data);
            
            if ($id) {
                echo json_encode(["status"=>"success","msg"=>"FB Page Subscription added successfully" ]);
            }
            
        }else{
            $update = FbPageSubscription::where('fb_id',$input['id'])->update($input_data);
            echo json_encode(["status"=>"success","msg"=>"FB Page Subscription updated successfully"]);
        }
    }

    public function save_token_globally(Request $request){
        $get_sec = $request->input('seconds');
        $sec ='';
        if(!empty($get_sec)){
            $sec = date("d-m-Y H:i A", strtotime("+".$get_sec." seconds"));
        }else{
            $sec = date("d-m-Y H:i A", strtotime("+60 days"));
        }
        
        $env_keys_save = DotenvEditor::setKeys([
            'FB_LOGIN_TOKEN_EXPIRE_ON' => $sec
        ]);
        $env_keys_save->save();
        echo $sec;
    }

    

    public function facebookPageSubscription(){
        $subscriptions = FbPageSubscription::all();
        return view('setting.pagesubscriptions',compact('subscriptions'));
    }

    public function fb_page_subscription_delete($fbsubs_id){
        
        $response = FbPageSubscription::where('fb_id',$fbsubs_id);
        $response->delete();
        
        return back()->with('success','Subscription deleted successfully');
    }

    public function fb_page_subscription_api(Request $request){
        $search = $request->input('search');
        $serach_value = $search['value'];
        $offset = $request->input('start');/** offset value * */
        $limit = $request->input('length');/** limits for datatable (show entries) * */
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        
        $subscriptions = FbPageSubscription::select('*');
        
        if(!empty($serach_value))
        {
            $subscriptions->where(function($query) use ($serach_value){
                $query->where('subscribed_page_name','LIKE',"%".$serach_value."%")
                ->orwhere('category','like',"%".$serach_value."%")
                ;
            });                
        }
        $count = $subscriptions->get()->count();
        $subscriptions = $subscriptions->offset($offset)->limit($limit);
        if(isset($request->input('order')[0]['column'])){
            $data = ['fb_id','subscribed_page_name','category'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $subscriptions->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else{
            $subscriptions->orderBy('fb_id','desc');
        }

        $subscriptions_data = $subscriptions->get()->toArray();
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $subscriptions_data; 

        echo json_encode($array);
    }
}
