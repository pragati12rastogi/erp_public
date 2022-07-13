<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\DismissedAnnouncement;
use DB;
use Auth;
use Validator;
use App\Models\User;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $announcements = Announcement::with('created_by_user')->get();
        return view('announcement.index',compact('users','announcements'));
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
                'name' =>'required',
                'message' => 'required',
                'user_ids.*' => 'required'
            ],[
                'name.required' => 'Name is required',
                'message.required' => 'Message is required',
                'user_ids.*.required' => 'Users are required'
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
            
            $exp = new Announcement();
            $input['created_by'] = Auth::id();
            $exp->create($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Announcement is created successfully');
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
        $announcement = Announcement::findOrFail($id);
        echo json_encode(compact('announcement'));
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
            $exp = Announcement::findOrFail($id);
            
            $validation = Validator::make($input,[
                'name' =>'required',
                'message' => 'required',
                'user_ids.*' => 'required'
            ],[
                'name.required' => 'Name is required',
                'message.required' => 'Message is required',
                'user_ids.*.required' => 'Users are required'
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
            
            $exp->update($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Announcement is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $announcement = Announcement::find($id);
        $announcement->delete();

        $dismiss = DismissedAnnouncement::where('announcement_id',$id)->delete();
        return back()->with('success','Announcement deleted successfully');
    }

    public function dismissAnnouncement($id){
        $input= [
            'announcement_id'=>$id,
            'user_id'=>Auth::id()
        ];
        $dis = new DismissedAnnouncement();
        $dis->create($input);
        return back();
    }

    
}
