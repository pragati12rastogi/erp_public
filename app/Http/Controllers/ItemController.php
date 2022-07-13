<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\Item;
use App\Models\ItemPhoto;
use App\Models\GstPercent;
use App\Models\Category;
use App\Models\Hsn;
use Image;
use Validator;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $gsts = GstPercent::all();
        $hsns = Hsn::all();
        $items = Item::orderBy('id','Desc')->get();
        return view('item.index',compact('items','categories','gsts','hsns'));
    }

    public function item_list_api(Request $request){
        $search = $request->input('search');
        $serach_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        $s_date = $request->input('startDate');
        $e_date = $request->input('endDate');

        $items = Item::with('images')->leftjoin('hsn','hsn.id','items.hsn_id')
        ->leftjoin('categories','categories.id','items.category_id')
        ->leftjoin('gst_percents','gst_percents.id','items.gst_percent_id')
        ->leftjoin('users as created_by_user','created_by_user.id','items.created_by')
        ->leftjoin('users as updated_by_user','updated_by_user.id','items.updated_by')
        ->select('items.*','hsn.hsn_no','categories.name as cat_name','gst_percents.percent','created_by_user.name as created_by_name','updated_by_user.name as updated_by_name')
        ;
        
        if(!empty($s_date) && empty($e_date))
        {
            $items->where(function($query) use ($s_date){
                $query->where('items.created_at','>=',$s_date);
            });                
        }else if(!empty($e_date) && empty($s_date)){
            $items->where(function($query) use ($e_date){
                $query->where('items.created_at','<=',$e_date);
            });   
        }else if(!empty($e_date) && !empty($s_date)){
            $items->where(function($query) use ($s_date,$e_date){
                $query->whereBetween('items.created_at',array($s_date,$e_date));
            });   
        }

        if(!empty($serach_value))
        {
            $items->where(function($query) use ($serach_value){
                $query->where('name','LIKE',"%".$serach_value."%")
                ->orWhere('hsn.hsn_no','LIKE',"%".$serach_value."%")
                ->orWhere('categories.name','LIKE',"%".$serach_value."%")
                ->orWhere('gst_percents.percent','LIKE',"%".$serach_value."%")
                ->orWhere('created_by_user.name','LIKE',"%".$serach_value."%")
                ->orWhere('updated_by_user.name','LIKE',"%".$serach_value."%")
                ;
            });                
        }
        
        $count = $items->get()->count();
        $items = $items->offset($offset)->limit($limit);
        if(isset($request->input('order')[0]['column'])){
            $data = ['id','name','categories.name','id','hsn.hsn_no','gst_percents.percent','created_at','created_by_user.name'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $items->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else{
            $items->orderBy('id','desc');
        }

        $items_data = $items->get()->toArray();
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $items_data; 
        return json_encode($array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $gsts = GstPercent::all();
        $hsns = Hsn::all();
        return view('item.create',compact('categories','gsts','hsns'));
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
                'category_id' => 'required',
                'photo.*' => 'required|mimes:jpeg,jpg,png',
                'hsn_id' => 'required',
                'gst_percent_id' => 'required'
            ],[
                'name.required'=>'Name is required',
                'category_id.required'=>'Category is required',
                'photo.*.required'=>'Photo is required',
                'photo.*.mimes'=>'Photo Accept only jpeg,png,jpg extensions',
                'hsn_id.required'=>'Hsn is required',
                'gst_percent_id.required'=>'Gst is required'
            ]);
            if($validation->fails()){
                
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.' ';
                    
                }
                
                return back()->with('error',$message);
                
            }
            DB::beginTransaction();
            $item = new Item();
            
            $input['created_by'] = Auth::id();
            $getid = $item->create($input);
            
            foreach ($input['photo'] as $f => $file ) {
                
                $optimizeImage = Image::make($file);
                $optimizePath = public_path() . '/uploads/items/';
                $image = Str::random(5).time() .'.'. $file->getClientOriginalExtension();
                
                $optimizeImage->save($optimizePath . $image, 90);
    
                ItemPhoto::insert([
                    'item_id'=>$getid->id,
                    'photo' => $image
                ]);
                
            }

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Item is created successfully');
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
        $categories = Category::all();
        $gsts = GstPercent::all();
        $hsns = Hsn::all();
        $item = Item::findOrFail($id);
        return view('item.edit',compact('categories','gsts','hsns','item'));
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
                'name' => 'required',
                'category_id' => 'required',
                'photo.*' => 'mimes:jpeg,jpg,png',
                'hsn_id' => 'required',
                'gst_percent_id' => 'required'
            ],[
                'name.required'=>'Name is required',
                'category_id.required'=>'Category is required',
                
                'photo.*.mimes'=>'Photo Accept only jpeg,png,jpg extensions',
                'hsn_id.required'=>'Hsn is required',
                'gst_percent_id.required'=>'Gst is required'
            ]);

            if($validation->fails()){
                
                $validation_arr = $validation->errors();
                $message = '';
                foreach ($validation_arr->all() as $key => $value) {
                    $message .= $value.' ';
                    
                }
                
                return back()->with('error',$message);
                
            }

            DB::beginTransaction();
            $item = Item::findOrFail($id);
            
            if(!isset($input['photo']) && count($item->images)<= 0){
                return back()->with('error','Item image is required please select any product image');
            }
            if(isset($input['photo'])){
                foreach ($input['photo'] as $f => $file ) {
                    
                    $optimizeImage = Image::make($file);
                    $optimizePath = public_path() . '/uploads/items/';
                    $image = Str::random(5).time() .'.'. $file->getClientOriginalExtension();
                    
                    $optimizeImage->save($optimizePath . $image, 90);
        
                    ItemPhoto::insert([
                        'item_id' => $id,
                        'photo' => $image
                    ]);
                    
                }
            }
            
            $input['updated_by'] = Auth::id();
            $item->update($input);

        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','Something went wrong '.$th->getMessage());
        }
        DB::commit();
        return back()->with('success','Item is updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        if(count($item->images)>0){
            foreach($item->images as $i => $photo){
                if ($photo->photo != '' && file_exists(public_path() . '/uploads/items/' . $photo->photo)) {
                    unlink(public_path() . '/uploads/items/' . $photo->photo);
                }
            }
        }
        ItemPhoto::where('item_id',$id)->delete();
        $value = $item->delete();

        if ($value) {
            return back()->with('success','Item is deleted successfully.');
        }
    }

    public function delete_item_photo($id){
        $photo = ItemPhoto::find($id);

        if ($photo->photo != '' && file_exists(public_path() . '/uploads/items/' . $photo->photo)) {
            unlink(public_path() . '/uploads/items/' . $photo->photo);
        }
        
        $photo->delete();
        return back()->with('success','Image deleted successfully');
    }
}
