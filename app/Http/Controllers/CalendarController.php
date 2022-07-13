<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Auth;
use DB;
use Redirect,Response;
use Validator;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = [];
        if(is_admin(Auth::user()->role_id)){
            $data = Event::get();
        }else{
            $data = Event::where('created_by',Auth::id())->orWhere('public_event',1)->get();
        }
        
        if($data->count()) {
            foreach ($data as $key => $value) {
                $events[] = \Calendar::event(
                    $value->title,
                    true,
                    new \DateTime($value->start_date),
                    new \DateTime($value->end_date.' +1 day'),
                    $value->id,
                    // Add color and link on event
                    [
                        'color' => $value->event_color,
                        'url' => '#',
                        'start_date'=>$value->start_date,
                        'end_date'=>$value->end_date,
                        'description'=>$value->description,
                        'action'=>'calendar/'.$value->id,
                        'public_event'=>$value->public_event
                    ]
                );
            }
        }
        
        $calendar = \Calendar::addEvents($events)
        ->setCallbacks([
            'eventClick' => 'function(event){
                var event = event.event._def;
                editModal(event.title,event.extendedProps.action,event.extendedProps.description,event.extendedProps.start_date,event.extendedProps.end_date,event.ui.backgroundColor,event.extendedProps.public_event);
            }',
            'dateClick' => 'function() {
                addModal();
            }',
        ]);

        return view('calendar.index',compact('calendar'));
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
                'title' => 'required',
                'start_date' => 'required',
            ],[
                'title.required'=>'Title is required',
                'start_date.required'=>'Start Date is required'
                
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
            if(!empty($input['end_date'])){
                if(strtotime($input['start_date']) > strtotime($input['end_date'])){
                    return back()->with('error','Start date cant be greater than end date');
                }
            }
            $input['created_by'] = Auth::id();
            $event = Event::create($input);
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Event is created successfully');

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
        //
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
                'title' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ],[
                'title.required'=>'Title is required',
                'start_date.required'=>'Start Date is required',
                'end_date.required'=>'End Date is required'
                
            ]);
            $event = Event::findOrFail($id);

            if($validation->fails()){
                
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.', ';
                }
                
                return back()->with('error',$message);
                
            }
            DB::beginTransaction();
            if(!empty($input['end_date'])){
                if(strtotime($input['start_date']) > strtotime($input['end_date'])){
                    return back()->with('error','Start date cant be greater than end date');
                }
            }
            
            $event->update($input);
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Event is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return back()->with('success','Event is deleted successfully');
    }
}
