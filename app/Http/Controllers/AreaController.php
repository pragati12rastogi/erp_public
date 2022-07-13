<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\District;
use App\Models\Area;
use DB;
use Auth;
use Validator;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::all();
        $districts = District::all();
        $areas = Area::all();
        return view('area.index',compact('states','districts','areas'));
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
                'state_id' => 'required',
                'district_id' => 'required',
                'name' =>'required|unique:areas'
            ],[
                'state_id.required'=>'State is required',
                'district_id.required'=>'District is required',
                'name.required'=>'Area Name is required',
                'name.unique'=>'Area Name is already present',
                
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
            $area = new Area();
            $input['created_by'] = Auth::id();
            $area->create($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Area is created successfully');

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
        $area = Area::find($id);
        
        echo json_encode(compact('area'));
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
                'state_id' => ['required'],
                'district_id' => ['required'],
                'name' =>['required','unique:areas,name,'.$id.',id']
            ],[
                'state_id.required'=>'State is required',
                'district_id.required'=>'District is required',
                'name.required'=>'Area Name is required',
                'name.unique'=>'Area Name is already present',
                
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
            $area = Area::findOrFail($id);
            $input['updated_by'] = Auth::id();
            $area->update($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Area is updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        return back()->with('success','Area is deleted successfully');
    }

    public function getDistrictByState(Request $request){
        $state_id = $request->state_id;
        if(!empty($state_id)){
            $items = District::where('state_id',$state_id)->get()->toArray();

            echo json_encode(['status'=>'success','data'=>$items]);
        }else{
            echo json_encode(['status'=>'error']);
        }
    }

    public function getAreaByDistrict(Request $request){
        $district_id = $request->district_id;
        if(!empty($district_id)){
            $items = Area::where('district_id',$district_id)->get()->toArray();

            echo json_encode(['status'=>'success','data'=>$items]);
        }else{
            echo json_encode(['status'=>'error']);
        }
    }
}
