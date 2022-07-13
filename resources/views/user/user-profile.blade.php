@extends('layouts.master')
@section('title', 'User Creation')

@push('style')

@endpush

@push('custom-scripts')
{!! Html::script('/js/area_district.js') !!}
<script>
    $(function(){

        jQuery('#user_form').validate({ // initialize the plugin
            rules: {

                role: {
                    required: true,
                },
                name:{
                    required:true,
                },
                email:{
                    required:true,
                },
                mobile:{
                    required:true,
                },
                firm_name:{
                    required:true
                },
                gst_no:{
                    required:true
                },
                state_id:{
                    required:true
                },
                district:{
                    required:true
                },
                area_id:{
                    required:true
                },
                address:{
                    required:false
                },
                @if(!is_admin(Auth::user()->role_id))    
                bank_name:{
                    required:true
                },
                name_on_passbook:{
                    required:true
                },
                ifsc:{
                    required:true
                },
                account_no:{
                    required:true
                },
                @endif
                pan_no:{
                    required:true
                }
            },
            errorPlacement: function(error,element)
            {
                if($(element).attr('type') == 'radio')
                {
                    error.insertAfter(element.parent());
                }
                else if($(element).is('select'))
                {
                    error.insertAfter(element.parent());
                }
                else{
                    error.insertAfter(element);
                }
                    
            }
        });

        jQuery('#password_form').validate({ // initialize the plugin
            rules: {

                current_password: {
                    required: true,
                },
                new_password:{
                    required:true,
                    minlength:8
                },
                password_confirmation:{
                    required:true,
                    equalTo: "#new_password"
                }
            },
            errorPlacement: function(error,element)
            {
                if($(element).attr('type') == 'radio')
                {
                    error.insertAfter(element.parent());
                }
                else if($(element).is('select'))
                {
                    error.insertAfter(element.parent());
                }
                else{
                    error.insertAfter(element);
                }
                    
            }
        });

        
    })
</script>
@endpush

@section('content')

<div class="row">
    <div class="col-md-12">
        @include('flash-msg')
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="border-bottom mb-3 row">
                    <div class="col-md-9">
                        <h4 class="card-title">Manage Profile</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form id="user_form" method="post" enctype="multipart/form-data" action="{{url('user-profile/update/'.Crypt::encrypt($user->id))}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <input type="hidden"  name="role" value="{{$user->role->name}}">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            User Role: <span class="required">*</span>
                                        </label>
                                        <select disabled class="form-control select2">
                                            <option value="">Select Role</option>
                                            @foreach($roles as $r)
                                            <option value="{{$r->name}}" {{($user->role->name == $r->name)?'selected':''}}>{{$r->name}}</option>
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
                                        <input name="name" type="text" maxlength="255" value="{{$user->name}}" class="form-control text-capitalize" placeholder="Jhon Doe" >
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
                                        <input name="email" disabled type="email" maxlength="255" value="{{$user->email}}" class="form-control text-lowercase" placeholder="jhondoe@gmail.com" >
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
                                        <input name="mobile" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" value="{{$user->mobile}}" class="form-control" placeholder="9999888777" >
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
                                        <input name="firm_name" type="text" maxlength="255" value="{{$user->firm_name}}" class="form-control " placeholder="xyz Company" >
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
                                        <input name="gst_no" type="text" maxlength="100" value="{{$user->gst_no}}" class="form-control" placeholder="123abcdefghijk" >
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
                                        <select name="state_id" class="form-control select2"  onchange="get_district(this,'upd')">
                                            <option value="">Select State</option>
                                            @foreach($states as $s)
                                            <option value="{{$s->id}}" {{ ($user->state_id==$s->id) ? 'selected':'' }}>{{$s->name}}</option>
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
                                            District: <span class="required">*</span>
                                        </label>
                                        <select name="district" id="district_id_upd" class="form-control select2" onchange="get_area(this,'upd')">
                                            <option value="">Select District</option>
                                            @foreach($districts as $d)
                                            <option value="{{$d->id}}" {{ ($user->district==$d->id) ? 'selected':'' }}>{{$d->name}}</option>
                                            @endforeach
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
                                        <select name="area_id" id="area_id_upd" class="form-control select2" >
                                            <option value="">Select Area</option>
                                            @foreach($areas as $a)
                                            <option value="{{$a->id}}" {{ ($user->area_id==$a->id) ? 'selected':'' }}>{{$a->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="address">
                                            Address: 
                                        </label>
                                        <textarea rows="5" cols="10"  type="text" name="address"  class="form-control">{{$user->address}}</textarea>
                                        <small class="txt-desc">(Please Enter Address)</small>
                                        @error('address')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="control-label" for="address">
                                                    Profile Picture: 
                                                </label>
                                                <br>
                                                <input type="file" id="profile" name="image" accept="image/*">
                                                <br>
                                                <small class="txt-desc">(Please Choose Profile image)</small>
                                                @error('profile')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="card-inverse-secondary">
                                                <center>
                                                    @if($user->profile != '' && file_exists(public_path().'/uploads/user_profile/'.$user->profile))
                                                        <img src="{{url('/uploads/user_profile/'.$user->profile)}}">
                                                    @endif
                                                </center>    
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <h4>Bank Details</h4>
                                </div>
                                
                                @if(!is_admin(Auth::user()->role_id))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="firm_name">
                                                Bank Name: <span class="required">*</span>
                                            </label>
                                            <input name="bank_name" type="text" maxlength="255" value="{{$user->bank_name}}" class="form-control " placeholder="xyz Company" >
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
                                            <input name="name_on_passbook" type="text" maxlength="255" value="{{$user->name_on_passbook}}" class="form-control " placeholder="xyz Company" >
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
                                                Ifsc: <span class="required">*</span>
                                            </label>
                                            <input name="ifsc" type="text" maxlength="255" value="{{$user->ifsc}}" class="form-control " placeholder="xyz Company" >
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
                                            <input name="account_no" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" type="text" maxlength="255" value="{{$user->account_no}}" class="form-control " placeholder="xyz Company" >
                                            @error('account_no')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="firm_name">
                                            PAN Number: <span class="required">*</span>
                                        </label>
                                        <input name="pan_no" type="text" maxlength="255" value="{{$user->pan_no}}" class="form-control " placeholder="xyz Company" >
                                        @error('pan_no')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dark ">Update</button> 
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="border-bottom mb-3 row">
                    <div class="col-md-9">
                        <h4 class="card-title">Update Password</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form id="password_form" method="post" enctype="multipart/form-data" action="{{url('update/user-profile/password')}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Current Password: <span class="required">*</span>
                                        </label>
                                        <input name="current_password" type="password" maxlength="255" class="form-control text-capitalize" placeholder="**********" >
                                        
                                        @error('current_password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            New Password: <span class="required">*</span>
                                        </label>
                                        <input name="new_password" id="new_password" type="password" maxlength="255" class="form-control text-capitalize" placeholder="**********" >
                                        
                                        @error('new_password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Confirm Password: <span class="required">*</span>
                                        </label>
                                        <input name="password_confirmation" type="password" maxlength="255" class="form-control text-capitalize" placeholder="**********" >
                                        
                                        @error('password_confirmation')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                               
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dark ">Update Password</button> 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection