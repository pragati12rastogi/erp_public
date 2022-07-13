<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Items;
use DB;
use Auth;
use Validator;
use Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        
        return view('category.index',compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.create');
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
                'name'=>'required|unique:categories',
                'photo'=>'mimes:jpeg,png,jpg'
            ],[
                'name.required'=>'Name is required',
                'name.unique'=>'Category name is already existing',
                'photo.mimes' => 'Photo Accept only jpeg,png,jpg extensions'
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
            $category = new Category();
    
            if ($file = $request->file('photo')) {
    
                $optimizeImage = Image::make($file);
                $optimizePath = public_path() . '/uploads/category/';
                $image = time() .'.'. $file->getClientOriginalExtension();
                
                $optimizeImage->save($optimizePath . $image, 90);
    
                $input['image'] = $image;
    
            }
            $input['created_by'] = Auth::id();
            $category->create($input);


        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','some error occurred'.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Category created successfully.');
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
        $category = Category::findOrFail($id);
        return view('category.edit',compact('category','categories'));
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
                'name'=>'required|unique:categories,name,'.$id.',id',
                'photo'=>'mimes:jpeg,png,jpg,gif'
            ],[
                'name.required'=>'Name is required',
                'name.unique'=>'Category name is already existing',
                'photo.mimes' => 'Photo Accepts only jpeg,png,jpg,gif extensions'
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
            $category = Category::findOrFail($id);
    
            if ($file = $request->file('photo')) {
                
                if ($category->image != '' && file_exists(public_path() . '/uploads/category/' . $category->image)) {
                    unlink(public_path() . '/uploads/category/' . $category->image);
                }

                $optimizeImage = Image::make($file);
                $optimizePath = public_path() . '/uploads/category/';
                $image = time() .'.'. $file->getClientOriginalExtension();
                
                $optimizeImage->save($optimizePath . $image, 90);
    
                $input['image'] = $image;
    
            }
            $input['updated_by'] = Auth::id();
            $category->update($input);


        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollback();
            return back()->with('error','some error occurred'.$th->getMessage());
        }

        DB::commit();
        return back()->with('success','Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (count($category->items) > 0) {
            
            return back()->with('error','Category cant be deleted as its linked to items !');
        }

        if ($category->image != '' && file_exists(public_path() . '/uploads/category/' . $category->image)) {
            unlink(public_path() . '/uploads/category/' . $category->image);
        }

        $value = $category->delete();
        if ($value) {
            return back()->with('success','Category Has Been Deleted.');
        }
    }
}
