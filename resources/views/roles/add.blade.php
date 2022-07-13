
<div id="add_role_modal" class="modal fade" role="dialog">
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
                        <form id="role_form" method="post" enctype="multipart/form-data" action="{{url('roles')}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Name:</strong>
                                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Permission:</strong>
                                        <br/>
                                        <div class="row">
                                        @foreach($permission as $value)
                                            @php 
                                                $permission_ids = explode(',',$value->sub_permission_id);
                                                $permission_name = explode(',',$value->sub_permission_name);
                                                $sub_permissions = array_combine($permission_ids,$permission_name);
                                            @endphp
                                            <ul class="col-md-6 list-unstyled">
                                                <li>
                                                    <label> <input type="checkbox" class="all_select_permission" id="all_{{ $value->master_name }}"> {{ $value->master_name }}</label>
                                                    <ul style="list-style: none;">
                                                        @foreach($sub_permissions as $p_id => $p_name)
                                                            @php 
                                                                $break_string = explode('.',$p_name);
                                                                
                                                            @endphp
                                                            @if(!in_Array('store',$break_string) && !in_Array('update',$break_string) )
                                                                <li><label >{{ Form::checkbox('permission[]', $p_id, false, array('class' => 'sub_permission all_'.$value->master_name )) }}{{ $p_name }}</label></li>
                                                            @else
                                                                <li class="d-none "><label >{{ Form::checkbox('permission[]', $p_id, true, array('class' => 'sub_permission all_'.$value->master_name)) }}
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
