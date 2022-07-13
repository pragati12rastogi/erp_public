<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\District;
use App\Models\Area;
use App\Models\ProductCharge;
use App\Models\Item;
use App\Models\User;
use DB;
use Auth;
use Validator;

class ProductChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_charge = ProductCharge::all();
        $states = State::all();
        $product = Item::orderBy('id','Desc')->get();
        return view('product_charge.index',compact('product_charge','states','product'));
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
                'area_id' => 'required',
                'product_id' => 'required',
                'charges' =>'required'
            ],[
                'state_id.required'=>'State is required',
                'district_id.required'=>'District is required',
                'area_id.required'=>'Area is required',
                'product_id.required'=>'Product is required',
                'charges.required'=>'Charge is required'
            ]);
            if($validation->fails()){
                
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.', ';
                }
                
                return back()->with('error',$message);
                
            }

            $check = ProductCharge::where(['state_id'=>$input['state_id'],'district_id'=>$input['district_id'],'area_id'=>$input['area_id'],'product_id'=>$input['product_id']])->first();

            if(!empty($check)){
                return back()->with('error','Charge for this Area, State and District is already present');
            }

            DB::beginTransaction();
            $area = new ProductCharge();
            $input['created_by'] = Auth::id();
            $area->create($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Product Charge is created successfully');

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
        $product_charge = ProductCharge::find($id);
        echo json_encode(compact('product_charge'));
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
                'state_id' => 'required',
                'district_id' => 'required',
                'area_id' => 'required',
                'product_id' => 'required',
                'charges' =>'required'
            ],[
                'state_id.required'=>'State is required',
                'district_id.required'=>'District is required',
                'area_id.required'=>'Area is required',
                'product_id.required'=>'Product is required',
                'charges.required'=>'Charge is required'
            ]);
            if($validation->fails()){
                
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.', ';
                }
                
                return back()->with('error',$message);
                
            }

            $check = ProductCharge::where(['state_id'=>$input['state_id'],'district_id'=>$input['district_id'],'area_id'=>$input['area_id'],'product_id'=>$input['product_id']])->where('id','<>',$id)->first();

            if(!empty($check)){
                return back()->with('error','Charge for this Area, State and District is already present');
            }

            DB::beginTransaction();
            $area = ProductCharge::findOrFail($id);
            $input['updated_by'] = Auth::id();
            $area->update($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Product Charge is updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prod = ProductCharge::find($id);
        $prod->delete();

        return back()->with('success','Product Charge is deleted sucessfully');
    }


    public function getItemCharge(Request $request){
        $input = $request->all();
        $user = User::where('id',$input['user_id'])->first();
        $prod = Item::leftjoin('product_charges',function($join)use($user){
            $join->on("items.id","=","product_charges.product_id")
            ->where(['state_id'=>$user->state_id,'district_id'=>$user->district,'area_id'=>$user->area_id]);
        })->select('items.*','product_charges.charges')->get()->toArray();
        
        echo json_encode(['data'=>$prod]);
    }
}
