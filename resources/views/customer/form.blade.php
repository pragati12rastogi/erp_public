@if(Auth::user()->hasPermissionTo('customers.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="add_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Customer</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="contracts_form" method="post" enctype="multipart/form-data" action="{{url('customers')}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Name: <span class="required">*</span>
                                        </label>
                                        <input type="text" id="c_name" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Email: <span class="required">*</span>
                                        </label>
                                        <input type="email" id="c_email" name="email" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Company: <span class="required">*</span>
                                        </label>
                                        <input type="text" id="c_company" name="company" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Phone: <span class="required">*</span>
                                        </label>
                                        <input type="tel"  name="phone" id="c_phone" minlength="9" maxlength="14" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Address: 
                                        </label>
                                        <textarea id="c_address" name="address" class="form-control" ></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            City: 
                                        </label>
                                        <input type="text"  name="city" id="c_city"  class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            State: 
                                        </label>
                                        <input type="text"  name="state" id="c_state"  class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Country: 
                                        </label>
                                        <input type="text"  name="country" id="c_country"  class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Zipcode: 
                                        </label>
                                        <input type="text"  name="pincode" id="c_pincode"  class="form-control" >
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

@if(Auth::user()->hasPermissionTo('customers.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Customer</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="customer_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            {{method_field('PUT')}}
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Name: <span class="required">*</span>
                                        </label>
                                        <input type="text" id="c_name_upd" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Email: <span class="required">*</span>
                                        </label>
                                        <input type="email" id="c_email_upd" name="email" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Company: <span class="required">*</span>
                                        </label>
                                        <input type="text" id="c_company_upd" name="company" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Phone: <span class="required">*</span>
                                        </label>
                                        <input type="tel"  name="phone" id="c_phone_upd" minlength="9" maxlength="14" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Address: 
                                        </label>
                                        <textarea id="c_address_upd" name="address" class="form-control" ></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            City: 
                                        </label>
                                        <input type="text"  name="city" id="c_city_upd"  class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            State: 
                                        </label>
                                        <input type="text"  name="state" id="c_state_upd"  class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Country: 
                                        </label>
                                        <input type="text"  name="country" id="c_country_upd"  class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Zipcode: 
                                        </label>
                                        <input type="text"  name="pincode" id="c_pincode_upd"  class="form-control" >
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