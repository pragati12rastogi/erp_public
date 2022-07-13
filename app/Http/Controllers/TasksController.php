<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskChecklist;
use App\Models\TaskAttachment;
use App\Models\User;
use Auth;
use DB;
use Validator;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status','1')->get();
        if(is_admin(Auth::user()->role_id)){
            $tasks = Task::get();
        }else{
            $tasks = Task::where('created_by',Auth::id())->get();
        }
        return view('task/index',compact('tasks','users'));
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
                'name'          => ['required'],
                'start_at'      => ['required'],
                'priority'      => ['required'],
                'assigned_to.*' => ['required'],
            ],[
                'name.required'          => 'Task Name is required',
                'start_at.required'      => 'Start Time is required',
                'priority.required'      => 'Priority is required',
                'assigned_to.*.required' => 'Task is not assigned to any user'
            ]);

            $input['status'] = 'In Progress';
            if( strtotime($input['start_at']) > strtotime(date('Y-m-d')) ){
                $input['status'] = 'Not Started';
            }
            $input['created_by'] = Auth::id();

            DB::beginTransaction();

            $task = Task::create($input);
            
            $task_id = $task->id;

            if(empty($task_id)){
                DB::rollback();
                return back()->with('error','Some error occurred, please try again');
            }else{

                if(isset($input['checklist']) && !empty($input['checklist']) ){
                    foreach($input['checklist'] as $check_ind => $check_data){
                        $checklist_data = [
                            'checklist' => $check_data,
                            'task_id'   => $task_id,
                            'created_by'=> $input['created_by']
                        ];
                        TaskChecklist::create($checklist_data);
                    }
                }

                if( !empty($input['task_attachment']) ){
                    if(!is_dir(public_path().'/task_attachments')){
                        mkdir(public_path().'/task_attachments');
                    }
                    foreach ($input['task_attachment'] as $f => $file ) {
                        
                        if( !empty($file) ){
                            $mimetype =  $file->getMimeType();
                            $destinationPath = public_path().'/task_attachments/';
                            $filenameWithExt = $file->getClientOriginalName();
                            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                            $extension = $file->getClientOriginalExtension();
                            $image = $filename.'_'.time().'.'.$extension;
                            $path = $file->move($destinationPath, $image);

                            TaskAttachment::create([
                                'task_id'   => $task_id,
                                'path'      => '/task_attachments/'.$image,
                                'file_type' => $mimetype,
                                'created_by'=> $input['created_by']
                            ]);
                        }
                    }
                }
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return back()->with('error','some error occurred'.$ex->getMessage());
        }
        
        DB::commit();
        return back()->with('success','Task is created successfully');
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
        $task = Task::find($id);
        $attachment = TaskAttachment::with('created_by_user')->where('task_id',$id)->get()->toArray();
        $checklist = TaskChecklist::with(['completed_by_user','created_by_user'])->where('task_id',$id)->get()->toArray();
        echo json_encode(compact('task','attachment','checklist'));
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
        try{
            $input = $request->all();
            
            $validation = Validator::make($input,[
                'name'          => ['required'],
                'start_at'      => ['required'],
                'priority'      => ['required'],
                'assigned_to.*' => ['required'],
            ],[
                'name.required'          => 'Task Name is required',
                'start_at.required'      => 'Start Time is required',
                'priority.required'      => 'Priority is required',
                'assigned_to.*.required' => 'Task is not assigned to any user'
            ]);

            
            DB::beginTransaction();
            $task = Task::findOrFail($id);
            $task->update($input);

            if(isset($input['checklist']) && !empty($input['checklist']) ){
                foreach($input['checklist'] as $check_ind => $check_data){
                    $checklist_data = [
                        'checklist' => $check_data,
                        'task_id'   => $id,
                        'created_by'=> Auth::id()
                    ];
                    TaskChecklist::create($checklist_data);
                }
            }

            /* if( !empty($input['task_attachment']) ){
                if(!is_dir(public_path().'/task_attachments')){
                    mkdir(public_path().'/task_attachments');
                }
                foreach ($input['task_attachment'] as $f => $file ) {
                    
                    if( !empty($file) ){
                        $mimetype =  $file->getMimeType();
                        $destinationPath = public_path().'/task_attachments/';
                        $filenameWithExt = $file->getClientOriginalName();
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $image = $filename.'_'.time().'.'.$extension;
                        $path = $file->move($destinationPath, $image);

                        TaskAttachment::create([
                            'task_id'   => $id,
                            'path'      => '/task_attachments/'.$image,
                            'file_type' => $mimetype
                        ]);
                    }
                }
            } */

            if(isset($input['upd_checklist'])){
                foreach($input['upd_checklist'] as $check_id => $check_data){
                    TaskChecklist::where('id',$check_id)->update([
                        'checklist'=> $check_data
                    ]);
                }
            }

            /* if(isset($input['task_completed'])){
                TaskChecklist::where('task_id',$id)->update(['status'=> 1,'completed_by'=>null]);
                foreach($input['task_completed'] as $list_id => $val){
                    TaskChecklist::where('id',$list_id)->update([
                        'status'=> 1,
                        'completed_by'=>Auth::id()
                    ]);
                }
            } */

        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return back()->with('error','some error occurred'.$ex->getMessage());
        }
        
        DB::commit();
        return back()->with('success','Task is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if(count($task->attachments)>0){
            foreach($task->attachments as $files){
                if (!empty($files->path) && file_exists(public_path() . $files->path)) {
                    unlink(public_path() . $files->path);
                }
            }
        }
        $checklist = TaskChecklist::where('task_id',$id)->delete();
        $attachments = TaskAttachment::where('task_id',$id)->delete();
        $task->delete();
        return back()->with('success','Task has been deleted !');

    }

    public function task_status_update(Request $request){
        
        $task = Task::find($request->id);
        if(!empty($task)){
            $task->update(['status'=>$request->status]);
            echo json_encode(['status'=>true,'msg'=>'Task Status updated successfully']);
        }else{
            echo json_encode(['status'=>false,'msg'=>'Task not found']);
        }
    }

    public function task_attachment_delete($id){
        $attachment = TaskAttachment::find($id);
        if (!empty($attachment->path) && file_exists(public_path() . $attachment->path)) {
            unlink(public_path() . $attachment->path);
        }
        $attachment->delete();
        
        echo json_encode(['status'=>true]);
    }

    public function task_checklist_delete($id){
        $checklist = TaskChecklist::find($id);
        $checklist->delete();
        
        echo json_encode(['status'=>true]);
    }

    public function task_upload_attachment(Request $request){
        $input = $request->all();
        
        if( !empty($file = $input['file']) ){
            $mimetype =  $file->getMimeType();
            $destinationPath = public_path().'/task_attachments/';
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $image = $filename.'_'.time().'.'.$extension;
            $path = $file->move($destinationPath, $image);

            DB::beginTransaction();
            

            $att_id = TaskAttachment::create([
                'task_id'   => $input['task_id'],
                'path'      => '/task_attachments/'.$image,
                'file_type' => $mimetype,
                'created_by'=> Auth::id()
            ]);

            if(!empty($att_id)){
                DB::commit();
                echo json_encode(['status'=> true,'att_id'=>$att_id->id,'att_data'=>$att_id]);
            }else{
                DB::rollback();
                echo json_encode(['status'=> false,'msg'=>'upload again some error occured']);
            }
        }else{
            DB::rollback();
            echo json_encode(['status'=> false,'msg'=>'file not found']);
        }

    }

    public function myTasksIndex(){
        $tasks = Task::whereRaw("Find_in_set(".Auth::id().",REPLACE(REPLACE(REPLACE(assigned_to,'[',''),']',''),'\"',''))")->get();
        return view('mytask/index',compact('tasks'));
    }

    public function myTasksView($id){
        $task = Task::with('created_by_user')->find($id);
        $attachment = TaskAttachment::with('created_by_user')->where('task_id',$id)->get()->toArray();
        $checklist = TaskChecklist::with(['completed_by_user','created_by_user'])->where('task_id',$id)->get()->toArray();
        $created_date = date('d-m-Y H:i:s',strtotime($task->created_at));
        $assignees_user = User::whereIn('id',$task->assigned_to)->get()->toArray();
        echo json_encode(compact('task','attachment','checklist','created_date','assignees_user'));
    }

    public function task_checklist_update($id){ 
        $checklist = TaskChecklist::find($id);
        if(!empty($checklist)){
            // if status 1 then change to 0
            if($checklist->status){
                $checklist->update([
                    'status'=> 0,
                    'completed_by'=>null
                ]);
            }else{
                // status is 0 so change it to 1
                $checklist->update([
                    'status'=> 1,
                    'completed_by'=>Auth::id()
                ]);
            }
            echo json_encode(['status'=>true]);
        }else{
            echo json_encode(['status'=>false,'msg' =>'Looks like checklist got deleted, refresh page and try again.']);
        }
    }

    public function switchToKanBan(){
        $data = [];
        $data['users'] = User::where('status','1')->get();
        
        $status = ['in_progress'=>'In Progress','not_started'=>'Not Started','testing'=>'Testing','awaiting_feedback'=>'Awaiting Feedback','complete'=>'Complete'];
        if(is_admin(Auth::user()->role_id)){
            foreach($status as $key => $value){
                $data[$key] = Task::with(['checklists','done_checklist'])->where('status',$value)->get();
            }
        }else{
            foreach($status as $key => $value){
                $data[$key] = Task::with(['checklists','done_checklist'])->where('created_by',Auth::id())->where('status',$value)->get();
            }
        }
        return view('task.kanban',$data);
    }

    public function mytaskSwitchToKanBan(){
        $status = ['in_progress'=>'In Progress','not_started'=>'Not Started','testing'=>'Testing','awaiting_feedback'=>'Awaiting Feedback','complete'=>'Complete'];
        $data = [];
        foreach($status as $key => $value){
            $data[$key] = Task::whereRaw("Find_in_set(".Auth::id().",REPLACE(REPLACE(REPLACE(assigned_to,'[',''),']',''),'\"',''))")->where('status',$value)->get();
        }
        return view('mytask.kanban',$data);
    }
    
    public function updateKanbanStatus(Request $request){
        
        $input = $request->input();
        $status = ['in_progress'=>'In Progress','not_started'=>'Not Started','testing'=>'Testing','awaiting_feedback'=>'Awaiting Feedback','complete'=>'Complete'];
        $moved_ele = explode('_',$input['moved_ele']);
        $id_index = count($moved_ele)-1;
        $id = $moved_ele[$id_index];
        $task = Task::find($id);
        $task->update(['status'=>$status[$input['moved_to']]]);

        echo json_encode(['status'=>'success']);
    }
}
