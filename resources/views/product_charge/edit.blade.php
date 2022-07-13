@if(Auth::user()->hasPermissionTo('product_charge.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="product_charge_edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Product Charge</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="product_charge_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            State Name: <span class="required">*</span>
                                        </label>
                                        <select name="state_id" id="state_id_upd" class="form-control select2" onchange="get_district(this,'upd')">
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
                                            District Name: <span class="required">*</span>
                                        </label>
                                        <select name="district_id" id="district_id_upd" class="form-control select2"  onchange="get_area(this,'upd')">
                                            <option value="">Select District</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                        Select Area: <span class="required">*</span>
                                        </label>
                                        <select name="area_id" id="area_id_upd" class="form-control select2" >
                                            <option value="">Select Area</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Select Product: <span class="required">*</span>
                                        </label>
                                        <select name="product_id" id="product_id_upd" class="form-control select2" >
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
                                        <input name="charges" id="charges_upd" type="number" min="0" value="" class="form-control"  >
                                        
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