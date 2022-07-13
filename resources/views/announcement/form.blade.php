@if(Auth::user()->hasPermissionTo('announcements.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="add_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Announcement</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="announcement_form" method="post" enctype="multipart/form-data" action="{{url('announcements')}}" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Name: <span class="required">*</span>
                                        </label>
                                        <input type="text" id="c_name" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Message: <span class="required">*</span>
                                        </label>
                                        <textarea name="message" class="form-control ckeditor" ></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Users: <span class="required">*</span>
                                        </label>
                                        <select name="user_ids[]" class="form-control select2" multiple>
                                            @foreach($users as $uid => $udata)
                                            <option value="{{$udata->id}}">{{$udata->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dark ">Save</button>  
                                <button type="reset" class="btn btn-inverse-dark" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(Auth::user()->hasPermissionTo('announcements.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Announcement</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="announcement_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}
                            {{method_field('PUT')}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Name: <span class="required">*</span>
                                        </label>
                                        <input type="text" id="c_name_upd" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Message: <span class="required">*</span>
                                        </label>
                                        <textarea name="message" id="c_details_upd" class="form-control ckeditor" ></textarea>
                            
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Users: <span class="required">*</span>
                                        </label>
                                        <select name="user_ids[]" id="user_ids_upd" class="form-control select2" multiple>
                                            @foreach($users as $uid => $udata)
                                            <option value="{{$udata->id}}">{{$udata->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dark ">Save</button>  
                                <button type="reset" class="btn btn-inverse-dark" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif