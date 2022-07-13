@if(Auth::user()->hasPermissionTo('leads.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="add_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Lead</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="add_form" method="post" enctype="multipart/form-data" action="{{url('leads')}}" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Status: <span class="required">*</span>
                                        </label>
                                        <select name="status" class="form-control select2">
                                            <option value="">Select status</option>
                                            @foreach($statuses as $sind => $sval)
                                                <option value="{{$sval->id}}">{{$sval->name}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Source: <span class="required">*</span>
                                        </label>
                                        <select name="source" class="form-control select2">
                                            <option value="">Select source</option>
                                            @foreach($sources as $sind => $sval)
                                                <option value="{{$sval->id}}">{{$sval->name}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" type="text" maxlength="255" value="{{old('name')}}" class="form-control"  >
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Email: <span class="required">*</span>
                                        </label>
                                        <input name="email" type="text" maxlength="255" value="" class="form-control"  >
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Mobile: <span class="required">*</span>
                                        </label>
                                        <input name="phonenumber" type="text" maxlength="13" minlength="9" value="" class="form-control"  >
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Assigned To: <span class="required">*</span>
                                        </label>
                                        <select name="assigned_to" class="form-control select2">
                                            <option value="">Select Assigned To</option>
                                            @foreach($users as $sind => $sval)
                                                <option value="{{$sval->id}}">{{$sval->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Zip Code: 
                                        </label>
                                        <input name="pincode" type="text" value="" class="form-control" >
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            City: 
                                        </label>
                                        <input name="city" type="text" value="" class="form-control"  >
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            State: 
                                        </label>
                                        <input name="state" type="text" value="" class="form-control"  >
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Country: 
                                        </label>
                                        <input name="country" type="text" value="" class="form-control"  >
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Lead Value: 
                                        </label>
                                        <input name="lead_value" type="number" value="" class="form-control"  >
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Company: 
                                        </label>
                                        <input name="company" type="text" value="" class="form-control" >
                                        
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Address: 
                                        </label>
                                        <textarea name="address" type="text" value="" class="form-control" ></textarea>
                                        
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Description: 
                                        </label>
                                        <textarea name="description" type="text" value="" class="form-control" ></textarea>
                                        
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

@if(Auth::user()->hasPermissionTo('leads.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Lead</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="edit_form" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Status: <span class="required">*</span>
                                        </label>
                                        <select name="status" id="edit_status" class="form-control select2">
                                            <option value="">Select status</option>
                                            @foreach($statuses as $sind => $sval)
                                                <option value="{{$sval->id}}">{{$sval->name}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Source: <span class="required">*</span>
                                        </label>
                                        <select name="source" id="edit_source" class="form-control select2">
                                            <option value="">Select source</option>
                                            @foreach($sources as $sind => $sval)
                                                <option value="{{$sval->id}}">{{$sval->name}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" id="edit_name" type="text" maxlength="255" value="" class="form-control"  >
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Email: <span class="required">*</span>
                                        </label>
                                        <input name="email" id="edit_email" type="text" maxlength="255" value="" class="form-control"  >
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Mobile: <span class="required">*</span>
                                        </label>
                                        <input name="phonenumber" id="edit_phonenumber" type="text" maxlength="13" minlength="9" value="" class="form-control"  >
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Assigned To: <span class="required">*</span>
                                        </label>
                                        <select name="assigned_to" id="edit_assigned_to" class="form-control select2">
                                            <option value="">Select Assigned To</option>
                                            @foreach($users as $sind => $sval)
                                                <option value="{{$sval->id}}">{{$sval->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            City: 
                                        </label>
                                        <input name="city" id="edit_city" type="text" value="" class="form-control"  >
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            State: 
                                        </label>
                                        <input name="state" id="edit_state"  type="text" value="" class="form-control"  >
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Country: 
                                        </label>
                                        <input name="country" id="edit_country" type="text" value="" class="form-control"  >
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Lead Value: 
                                        </label>
                                        <input name="lead_value" id="edit_lead_value" type="number" value="" class="form-control"  >
                                        
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Company: 
                                        </label>
                                        <input name="company" id="edit_company" type="text" value="" class="form-control" >
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Zip Code: 
                                        </label>
                                        <input name="pincode" id="edit_pincode" type="text" value="" class="form-control" >
                                        
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Address: 
                                        </label>
                                        <textarea name="address" id="edit_address" type="text" value="" class="form-control" ></textarea>
                                        
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Description: 
                                        </label>
                                        <textarea name="description" id="edit_description" type="text" value="" class="form-control" ></textarea>
                                        
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