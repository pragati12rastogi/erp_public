
<div id="edit_role_modal_{{$role->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Role</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="role_form_{{$role->id}}" method="post" enctype="multipart/form-data" action="{{route('roles.update', $role->id)}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Name:</strong>
                                        {!! Form::text('name', $role->name, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Permission:</strong>
                                        <br/>
                                        <div class="row">
                                            @php 
                                            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')->all();
                                            @endphp
                                        @foreach($permission as $value)
                                            @php 
                                                $permission_ids = explode(',',$value->sub_permission_id);
                                                $permission_name = explode(',',$value->sub_permission_name);
                                                $sub_permissions = array_combine($permission_ids,$permission_name);

                                                $per_intersect = array_intersect($permission_ids,$rolePermissions);

                                            @endphp
                                            <ul class="col-md-6 list-unstyled">
                                                <li>
                                                    <label> <input type="checkbox" class="all_select_permission_upd" id="upd_{{$role->id}}_all_{{ $value->master_name }}"  {{count($per_intersect) == count($permission_ids) ? 'checked':''}}> {{ $value->master_name }}</label>
                                                    <ul style="list-style: none;">
                                                        @foreach($sub_permissions as $p_id => $p_name)
                                                            @php 
                                                                $break_string = explode('.',$p_name);
                                                                
                                                            @endphp
                                                            @if(!in_Array('store',$break_string) && !in_Array('update',$break_string) )
                                                            <li><label >{{ Form::checkbox('permission[]', $p_id, in_array($p_id, $rolePermissions) ? true : false, array('class' => 'sub_permission_upd upd_'.$role->id.'_all_'.$value->master_name)) }}
                                                            {{ $p_name }}</label></li>
                                                            @else
                                                            <li class="d-none "><label >{{ Form::checkbox('permission[]', $p_id, in_array($p_id, $rolePermissions) ? true : false, array('class' => 'sub_permission_upd upd_'.$role->id.'_all_'.$value->master_name)) }}
                                                            {{ $p_name }}</label></li>

                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            </ul>
                                            
                                        @endforeach
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 ">
                                <hr>
                                <button type="submit" class="btn btn-dark mt-3">Save</button>
                            </div>
                        </form>
                    </div>
          
                </div>
            </div>
        </div>
    </div>
</div>

