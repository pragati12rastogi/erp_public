@if(Auth::user()->hasPermissionTo('vendors.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="add_vendor_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Vendor</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="vendor_form" method="post" enctype="multipart/form-data" action="{{url('vendors')}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Vendor Name: <span class="required">*</span>
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
                                        <input name="phone" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" value="{{old('phone')}}" class="form-control" placeholder="9999888777" >
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
                                            GST Number: 
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
                                        <select name="state" class="form-control select2" onchange="get_district(this)">
                                            <option value="">Select State</option>
                                            @foreach($states as $s)
                                            <option value="{{$s->id}}" {{ (old('state')==$s->id) ? 'selected':'' }}>{{$s->name}}</option>
                                            @endforeach
                                        </select>

                                        <small class="txt-desc">(Please select state)</small>
                                        @error('state')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="district">
                                            District: <span class="required">*</span>
                                        </label>
                                        <select name="district" id="district_id" class="form-control select2" >
                                            <option value="">Select District</option>
                                            
                                        </select>
                                        <small class="txt-desc">(Please select district)</small>
                                        @error('district')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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
                                
                            </div>
                            
                            <div class="modal-footer">
                                
                                <button type="submit" class="btn btn-dark">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif