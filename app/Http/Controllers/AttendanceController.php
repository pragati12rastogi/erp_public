<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Holiday;
use App\Models\Leave;
use DateTime;
use \Carbon\Carbon;

class AttendanceController extends Controller
{
    public function check_in_out_data(){
        $user = Auth::id();
        $current_date = date('Y-m-d');
        $attend = Attendance::where('user_id',$user)->where('date',$current_date)->first();
        $holiday = Holiday::where('date',$current_date)->first();
        $leave = Leave::where('from_date', '<=', $current_date)->where('to_date', '>=',$current_date)->where('user_id',$user)->where('approved',1)->first();
        if(!empty($holiday)){
            echo json_encode(['status'=>false,'data'=>'Today is holiday']);
        }else{
            if(!empty($leave)){
                echo json_encode(['status'=>false,'data'=>'Today you applied for leave']);
            }else{
                if(empty($attend))
                    echo json_encode(['status'=>false,'data'=>'']);
                else
                    echo json_encode(['status'=>true,'data'=>$attend]);
            }
        }
        
    }

    public function checkin(Request $request){
        $data['date'] = date('Y-m-d');
        $data['user_id'] = Auth::id();
        $data['intime'] = date('H:i:s');
        $attendance = Attendance::create($data);
        if($attendance){
            return back()->with('success','Your Attendance is marked');
        }else{
            return back()->with('error','Some error occurred while marking your attendance');
        }
        
    }

    public function checkOut(Request $request){
        $attendance = Attendance::where('user_id',Auth::id())->where('date',date('Y-m-d'))->first();
        $data['outtime'] = date('H:i:s');
        $attendance->update($data);
        if($attendance){
            return back()->with('success','User Marked out');
        }else{
            return back()->with('error','Some error occurred while marking your attendance');
        }
    }

    public function attendance_index(){
        $users = User::all();
        return view('attendance.index',compact('users'));
    }

    public function attendance_api(Request $request){
        $search = $request->input('search');
        $user_name = $request->input('user_name');
        $year_month = $request->input('year_month');
        $serach_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start;
        $limit = empty($limit) ? 10 : $limit;

        $user = Attendance::select('user_id')->get()->toArray();
        
        $a_date = date('Y-m-d');
        if ($year_month) {
            $month_year = date($year_month);
        }else{
            $month_year = date('Y-m');
        }
        
        $date = new DateTime($a_date);
        $date->modify('last day of this month');
        $last_day = $date->format('d'); 
        DB::enableQueryLog();
        $sort_data_query = array();

        for ($j = 1; $j <= $last_day ; $j++) {           
            $mdate = '"'.$month_year.'-'.$j.'"';
            
            $query[$j] = "IFNULL((SELECT CONCAT($j,',',att.intime,',',att.outtime,',',att.date) FROM attendance att WHERE att.user_id = attendance.user_id AND att.date = ".$mdate."),'$j') as d".$j." ";

            $holiday[$j] = "IFNULL((SELECT holi.name FROM holidays holi WHERE holi.date = ".$mdate."),'') as h".$j." ";
            $leave[$j] = "IFNULL((SELECT leav.reason FROM leaves leav WHERE leav.from_date <= ".$mdate." and leav.to_date >= ".$mdate." and leav.user_id = attendance.user_id and approved=1),'') as l".$j." ";
        }
        $query = join(",",$query);
        $holiday = join(",",$holiday);
        $leave = join(",",$leave);

        $date = Carbon::now();
        if ($year_month) {
            $month_arr = explode('-',$year_month);
            $month = $month_arr[1];
            $month_name = $date->format($month);
        }else{
            $month_name = date('m');  
        }
        $api_data = Attendance::leftJoin('users','attendance.user_id','users.id')
            ->whereMonth('attendance.date', date($month_name))
            ->select(
                'users.name',
                'attendance.id',
                'attendance.user_id',
                'attendance.intime',
                'attendance.outtime',
                'attendance.date as attendance_date',
                DB::raw("DATEDIFF(now(),attendance.date) as days"),
                DB::raw('DATE_FORMAT(attendance.date,"%Y-%m") as date'),
                DB::raw($query),
                DB::raw($holiday),
                DB::raw($leave)
            )->groupBy('attendance.user_id');

             if(!empty($serach_value)) {
                $api_data->where(function($query) use ($serach_value){
                    $query->where('attendance.id','like',"%".$serach_value."%")
                    ->orwhere('attendance.intime','like',"%".$serach_value."%")
                    ->orwhere('attendance.outtime','like',"%".$serach_value."%")
                    ->orwhere('attendance.user_id','like',"%".$serach_value."%")
                    ;
                });
            }
            if(isset($user_name)) {
               $api_data->where(function($query) use ($user_name){
                        $query->where('attendance.user_id',$user_name);
                    });               
            }
            if(isset($year_month) ) {
               $api_data->where(function($query) use ($year_month){
                        $query->whereYear('attendance.date', date($year_month));
                    });
            }
            
            if(isset($request->input('order')[0]['column'])) {

                $data = [
                    'attendance.user_id',
                    'users.name',
                    'attendance.intime',
                    'attendance.date'
                ];

                $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
                $api_data->orderBy($data[$request->input('order')[0]['column']], $by);
            }
            else
                $api_data->orderBy('attendance.user_id','asc');
            
            $count = count( $api_data->get()->toArray());
            $api_data = $api_data->offset($offset)->limit($limit)->get()->toArray(); 
            $array['recordsTotal'] = $count;
            $array['recordsFiltered'] = $count;
            $array['data'] = $api_data; 
            // dd($array);
            return json_encode($array);
    }
}
