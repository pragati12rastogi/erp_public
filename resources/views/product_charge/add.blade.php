@if(Auth::user()->hasPermissionTo('product_charge.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="add_product_charge_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Product Charge</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="product_charge_form" method="post" enctype="multipart/form-data" action="{{url('product_charge')}}" data-parsley-validate class="form-horizontal form-label-left">
                          {{csrf_field()}}
                          
                            <div class="row">
                              
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                        Select State: <span class="required">*</span>
                                        </label>
                                        <select name="state_id" class="form-control select2" onchange="get_district(this)">
                                            <option value="">Select State</option>
                                            @foreach($states as $sid => $s)
                                                <option value="{{$s->id}}">{{$s->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                        Select District: <span class="required">*</span>
                                        </label>
                                        <select name="district_id" id="district_id" class="form-control select2" onchange="get_area(this)">
                                            <option value="">Select District</option>
                                            
                                        </select>
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
                                        <label class="control-label" for="first-name">
                                            Select Product: <span class="required">*</span>
                                        </label>
                                        <select name="product_id" id="product_id" class="form-control select2" >
                                            <option value="">Select Product</option>
                                            @foreach($product as $ind => $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Charge: <span class="required">*</span>
                                        </label>
                                        <input name="charges" type="number" min="0" value="" class="form-control"  >
                                        
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