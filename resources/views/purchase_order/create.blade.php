<div class="col-lg-12 grid-margin stretch-card">
    
    <div class="card">


        <div class="card-body">
            <div class="border-bottom mb-3 row">
                <div class="col-md-10">
                    <h4 class="card-title">Create Purchase Order</h4>
                </div>
                
            </div>
            
            <div class="row">
                <div class="col-md-12">
                <form id="adminpurchase_form" method="post" enctype="multipart/form-data" action="{{url('purchase-order')}}" data-parsley-validate class="form-horizontal form-label-left">
                    {{csrf_field()}}
                    
                    <div class="row">
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="first-name">
                                    Select Vendor: <span class="required">*</span>
                                </label>
                                <select name="vendor_id" id="vendor_id" class="form-control select2">
                                    <option value=""> Select Vendor </option>
                                    @foreach( $vendors as $v_ind => $v)
                                        <option value = "{{$v->id}}">{{$v->name}}</option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="first-name">
                                    Applied GST: <span class="required">*</span>
                                </label>
                                <select name="gst_types[]" id="gst_types" data-placeholder="Select GST Types" class="form-control select2" multiple>
                                    <option value="igst"> IGST </option>
                                    <option value="sgst"> SGST </option>
                                    <option value="cgst"> CGST </option>
                                </select>
                                @error('gst_types')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label></label><br>
                            <button type="button" class="btn btn-sm btn-block btn-primary " onclick="return $('#products_model').modal('show');">Add Product</button>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="prod" readonly id="prod" style="opacity: 0;">
                        </div>

                        <div class="col-md-12" id="prod-append-div">

                        </div>

                    </div>
                    
                    <div class="col-xs-12 ">
                        <hr>
                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="products_model" class="delete-modal modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
        </div>
        <div class="modal-body text-center">
            <div class="table-responsive">
                <table id="item_table" class="table ">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Tax</th>
                        <th>Quantity</th>
                        <th>Add</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $key => $item)
                        <tr>
                            <td><input type="checkbox" id="checkbox_{{$item->id}}" class="multiple_item_select" value="{{$item->id}}"></td>
                            
                            <td>{{$item->category->name}}
                            <input type="hidden" id="modal_prod_cat_{{$item->id}}" value="{{$item->category->name}}">
                            </td>
                            <td>{{$item->name}}
                            <input type="hidden" id="modal_prod_name_{{$item->id}}" value="{{$item->name}}">
                            </td>
                            
                            <td>
                                <b>Rs. {{$item->stock->prod_price}}</b>
                                
                                <input type="hidden" id="modal_prod_price_{{$item->id}}" value="{{$item->stock->prod_price}}">
                            </td>
                            <td>
                                {{$item->gst_percent->percent}} %
                                <input type="hidden" id="modal_prod_gst_{{$item->id}}" value="{{$item->gst_percent->percent}}">
                            </td>
                            <td>
                                {{$item->stock->prod_quantity}}
                                <input type="hidden" id="modal_prod_qty_{{$item->id}}" value="{{$item->stock->prod_quantity}}">
                            </td>
                            
                            <td>
                                <div class="col-12">
                                    <div class="d-flex">
                                        <button type="button" class="inc_dec_btn btn btn-sm btn-dark btn-rounded">-</button>
                                        <input type="number" value="0" min="0" max="{{$item->stock->prod_quantity}}" class="form-control m-auto w-auto" id="item_prod_{{$item->id}}" onchange="qty_change_func(this)">
                                        <button type="button" class="inc_dec_btn btn btn-sm btn-dark btn-rounded">+</button>
                                        
                                    </div>
                                    <span class="error d-flex" id="qty_err_{{$item->id}}"></span>
                                </div>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 bg-inverse-primary p-1">
                <div class="row">
                    <div class="col-md-6">
                        <label class="m-0">Initial Price:</label> <span id="modal_total_price"></span><br>
                        <label class="m-0">Total GST:</label> <span id="modal_total_gst"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="m-0">Total Quantity:</label><span id="modal_total_quantity"></span><br>
                        <label class="m-0">Grand Total:</label><span id="modal_grand_total"></span><br>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="modal-footer">
            
            <button type="button" class="btn btn-success translate-y-3" id="modal-submit" >Submit</button>
            <button type="reset" class="btn btn-inverse-dark translate-y-3" data-dismiss="modal">Cancel</button>
        </form>
        </div>
    </div>
    </div>
</div>
