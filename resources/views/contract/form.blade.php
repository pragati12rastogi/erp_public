@if(Auth::user()->hasPermissionTo('contract.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="add_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Contract</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="contracts_form" method="post" enctype="multipart/form-data" action="{{url('contract')}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="customer">
                                            Customers : <span class="required">*</span>
                                        </label>
                                        <select name="user_id" class="form-control select2" >
                                            <option value="">Select Customer</option>
                                            @foreach($users as $uid => $u)
                                                <option value="{{$u->id}}" >{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Subject: <span class="required">*</span>
                                        </label>
                                        <input name="subject" type="text" maxlength="255"  class="form-control " placeholder="Subject" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Contract Value: <span class="required">*</span>
                                        </label>
                                        <input name="value" type="number" min="0"  class="form-control " >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Contract Type: <span class="required">*</span>
                                        </label>
                                        <select name="type" class="form-control select2">
                                            <option value="">Select Type</option>
                                            @foreach($types as $tid => $t)
                                                <option value="{{$t->id}}">{{$t->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Start Date: <span class="required">*</span>
                                        </label>
                                        <input type="date" name="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            End Date: <span class="required">*</span>
                                        </label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Description: 
                                        </label>
                                        <textarea name="description" class="form-control"></textarea>
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

@if(Auth::user()->hasPermissionTo('contract.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Contract</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="contracts_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            {{method_field('PUT')}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="customer">
                                            Customers : <span class="required">*</span>
                                        </label>
                                        <select name="user_id" id="user_id_upd" class="form-control select2" >
                                            <option value="">Select Customer</option>
                                            @foreach($users as $uid => $u)
                                                <option value="{{$u->id}}" >{{$u->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Subject: <span class="required">*</span>
                                        </label>
                                        <input name="subject" id="subject_upd" type="text" maxlength="255"  class="form-control " placeholder="Subject" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Contract Value: <span class="required">*</span>
                                        </label>
                                        <input name="value" id="value_upd" type="number" min="0"  class="form-control " >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Contract Type: <span class="required">*</span>
                                        </label>
                                        <select name="type" class="form-control select2" id="type_upd">
                                            <option value="">Select Type</option>
                                            @foreach($types as $tid => $t)
                                                <option value="{{$t->id}}">{{$t->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Start Date: <span class="required">*</span>
                                        </label>
                                        <input type="date" name="start_date" class="form-control" id="start_date_upd">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            End Date: <span class="required">*</span>
                                        </label>
                                        <input type="date" name="end_date" class="form-control" id="end_date_upd">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Description: 
                                        </label>
                                        <textarea name="description" class="form-control" id="description_upd"></textarea>
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

<div id="renew_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Renew Contract</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="renew_form" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Start Date: <span class="required">*</span>
                                        </label>
                                        <input type="date" id="start_date_renew" name="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            End Date: <span class="required">*</span>
                                        </label>
                                        <input type="date" id="end_date_renew" name="end_date" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Contract Value: <span class="required">*</span>
                                        </label>
                                        <input name="value" id="value_renew" type="number" min="0"  class="form-control" >
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="row" id="renew_table">
                                
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