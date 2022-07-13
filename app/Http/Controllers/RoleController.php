<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Custom\Constants;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::where('name','<>',Constants::ROLE_ADMIN)->get();
        $permission = Permission::select(DB::raw('GROUP_CONCAT(id) as sub_permission_id'), DB::raw('GROUP_CONCAT(name) as sub_permission_name'),'guard_name','master_name')->groupBy('master_name','guard_name')->get();
        
        return view('roles.index',compact('roles','permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::select(DB::raw('GROUP_CONCAT(id) as sub_permission_id'), DB::raw('GROUP_CONCAT(name) as sub_permission_name'),'guard_name','master_name')->groupBy('master_name','guard_name')->get();
        
        return view('roles.add',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ],[
            'name.required' => 'Name is required',
            'name.unique' => 'Name is already present',
            'permission' => 'Permission is required'
        ]); 
        if($validation->fails()){
                
            $validation_arr = $validation->errors();
            $message = '';
            foreach ($validation_arr->all() as $key => $value) {
                $message .= $value.', '; 
            }
            return back()->with('error',$message);
            
        } 
        
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
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
        $role = Role::find($id);
        $permission = Permission::select(DB::raw('GROUP_CONCAT(id) as sub_permission_id'), DB::raw('GROUP_CONCAT(name) as sub_permission_name'),'guard_name','master_name')->groupBy('master_name','guard_name')->get();
        
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('roles.edit',compact('role','permission','rolePermissions'));
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
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'permission' => 'required',
        ],[
            'name.required' => 'Name is required',
            'permission' => 'Permission is required'
        ]);
        if($validation->fails()){
                
            $validation_arr = $validation->errors();
            $message = '';
            foreach ($validation_arr->all() as $key => $value) {
                $message .= $value.', ';
                
            }
            
            return back()->with('error',$message);
            
        }

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
    
        $role->syncPermissions($request->input('permission'));
    
        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('role_id',$id)->get()->toArray();
        if(count($user)>0){
            return redirect()->route('roles.index')->with('warning','Users are assigned under this role, cannot delete!!');
        }

        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
