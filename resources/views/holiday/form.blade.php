@if(Auth::user()->hasPermissionTo('holiday.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="add_holiday_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Holiday</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="holiday_form" method="post" enctype="multipart/form-data" action="{{url('holiday')}}" data-parsley-validate class="form-horizontal form-label-left validateForm">
                          {{csrf_field()}}
                          
                            <div class="row">
                              
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Holiday Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" type="text" maxlength="255" class="form-control " >
                                        @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Date: <span class="required">*</span>
                                        </label>
                                        <input name="date" type="date" maxlength="255" class="form-control " >
                                        @error('date')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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

@if(Auth::user()->hasPermissionTo('holiday.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Holiday</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="hsn_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                              
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Holiday Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" id="name_upd" type="text" maxlength="255" class="form-control " >
                                        @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Date: <span class="required">*</span>
                                        </label>
                                        <input name="date" id="date_upd" type="date" maxlength="255" class="form-control " >
                                        @error('date')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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