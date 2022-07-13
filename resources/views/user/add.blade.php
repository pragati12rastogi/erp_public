@if(Auth::user()->hasPermissionTo('users.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="add_user_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add User</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="user_form" method="post" enctype="multipart/form-data" action="{{url('users')}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            User Role: <span class="required">*</span>
                                        </label>
                                        <select name="role" class="form-control select2">
                                            <option value="">Select Role</option>
                                            @foreach($roles as $r)
                                            <option value="{{$r->name}}" {{(old('role')==$r->name)?'selected':''}}>{{$r->name}}</option>
                                            @endforeach
                                        </select>
                                        <small class="txt-desc">(Please Choose User Role)</small>
                                        @error('role')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            User Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" type="text" maxlength="255" value="{{old('name')}}" class="form-control text-capitalize" placeholder="Jhon Doe" >
                                        @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="email">
                                            Email: <span class="required">*</span>
                                        </label>
                                        <input name="email" type="email" maxlength="255" value="{{old('email')}}" class="form-control text-lowercase" placeholder="jhondoe@gmail.com" >
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="email">
                                            Mobile: <span class="required">*</span>
                                        </label>
                                        <input name="mobile" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" value="{{old('mobile')}}" class="form-control" placeholder="9999888777" >
                                        @error('mobile')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="firm_name">
                                            Firm Name: <span class="required">*</span>
                                        </label>
                                        <input name="firm_name" type="text" maxlength="255" value="{{old('firm_name')}}" class="form-control " placeholder="xyz Company" >
                                        @error('firm_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="gst_no">
                                            GST Number: <span class="required">*</span>
                                        </label>
                                        <input name="gst_no" type="text" maxlength="100" value="{{old('gst_no')}}" class="form-control" placeholder="123abcdefghijk" >
                                        @error('gst_no')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="gst_no">
                                            State: <span class="required">*</span>
                                        </label>
                                        <select name="state_id" class="form-control select2" onchange="get_district(this)">
                                            <option value="">Select State</option>
                                            @foreach($states as $s)
                                            <option value="{{$s->id}}" {{ (old('state_id')==$s->id) ? 'selected':'' }}>{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                        <small class="txt-desc">(Please Choose State)</small>
                                        @error('state_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="district">
                                            Select District: <span class="required">*</span>
                                        </label>
                                        
                                        <select name="district" id="district_id" class="form-control select2" onchange="get_area(this)">
                                            <option value="">Select District</option>
                                            
                                        </select>

                                        <small class="txt-desc">(Please Select District)</small>
                                        @error('district')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                        Select Area: <span class="required">*</span>
                                        </label>
                                        <select name="area_id" id="area_id" class="form-control select2" >
                                            <option value="">Select Area</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="address">
                                            Address: 
                                        </label>
                                        <textarea rows="5" cols="10"  type="text" name="address" value="{{old('address')}}" class="form-control"></textarea>
                                        <small class="txt-desc">(Please Enter Address)</small>
                                        @error('address')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="address">
                                            Profile Picture: 
                                        </label>
                                        <br>
                                        <input type="file"  name="image" accept="image/*">
                                        <br>
                                        <small class="txt-desc">(Please Choose Profile image)</small>
                                        @error('profile')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <h4>Bank Details</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="firm_name">
                                            Bank Name: <span class="required">*</span>
                                        </label>
                                        <input name="bank_name" type="text" maxlength="255" value="" class="form-control " placeholder="xyz Company" >
                                        @error('bank_name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="firm_name">
                                            Name on passbook: <span class="required">*</span>
                                        </label>
                                        <input name="name_on_passbook" type="text" maxlength="255" value="" class="form-control " placeholder="xyz Company" >
                                        @error('name_on_passbook')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="firm_name">
                                            IFSC: <span class="required">*</span>
                                        </label>
                                        <input name="ifsc" type="text" maxlength="255" value="" class="form-control " placeholder="xyz Company" >
                                        @error('ifsc')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="firm_name">
                                            Account Number: <span class="required">*</span>
                                        </label>
                                        <input name="account_no" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" type="text" maxlength="255" value="" class="form-control " placeholder="xyz Company" >
                                        @error('account_no')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="firm_name">
                                            PAN Number: <span class="required">*</span>
                                        </label>
                                        <input name="pan_no"  type="text" maxlength="255" value="" class="form-control " placeholder="xyz Company" >
                                        @error('pan_no')
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
