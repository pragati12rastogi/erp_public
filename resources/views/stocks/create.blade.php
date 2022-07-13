@if(Auth::user()->hasPermissionTo('stocks.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
            
<div id="stock_add_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Stock</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="stock_form" method="post" enctype="multipart/form-data" action="{{url('stocks')}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Category: <span class="required">*</span>
                                        </label>
                                        <select name="category_id" class="form-control select2" id="category_id">
                                            <option>Select Category</option>
                                            @foreach($category as $cat_i => $cat)
                                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Items: <span class="required">*</span>
                                        </label>
                                        <select name="item_id" class="form-control select2" id="item_id">
                                            <option>Select Items</option>
                                            
                                        </select>
                                        @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12" id="item_details" >
                                    <div class="row">
                                        <div class="col-md-2" id="img_div">
                                            <center>
                                                <img src="{{asset('images/no-image.jpg')}}" id="item_img" class="img-lg img-thumbnail" >
                                            </center>
                                            
                                        </div>
                                        <div class="col-md-4" id="img_div">
                                            <label>GST :</label>
                                            <select name="gst_id" id="gst_id" disabled class="form-control select2">
                                                <option value="">Select GST</option>
                                                @foreach($gsts as $gst_i => $gst)
                                                <option value="{{$gst->id}}">{{$gst->percent}}%</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6" id="img_div">
                                            <label>HSN :</label>
                                            <select name="hsn_id" id="hsn_id" disabled class="form-control select2">
                                                <option value="">Select HSN</option>
                                                @foreach($hsn as $hid => $h)
                                                <option value="{{$h->id}}">{{$h->hsn_no}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Product Quantity: <span class="required">*</span>
                                        </label>
                                        <input type="number" value="0" min="0" name="prod_quantity" id="prod_quantity" class="form-control">
                                        @error('prod_quantity')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Per Product Price: <span class="required">*</span>
                                        </label>
                                        <input type="number" value="0" min="0" name="prod_price" id="prod_price" class="form-control">
                                        @error('prod_price')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Total Price: <span class="required">*</span>
                                        </label>
                                        <input type="number" readonly value="0" min="0" name="total_price" id="total_price" class="form-control">
                                        <small id="total_price_span"></small>
                                        @error('total_price')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Per Fright Charge: <span class="required">*</span>
                                        </label>
                                        <input type="number" value="0" min="0" name="per_freight_price" id="per_freight_price" class="form-control">
                                        @error('per_freight_price')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            User Percent: <span class="required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" value="0" min="0" max="100" name="user_percent" id="user_percent" class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text p-2">
                                                <i class="mdi mdi-percent"></i>
                                                </span>
                                            </div>
                                        </div> 
                                        @error('user_percent')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Final Price: <span class="required">*</span>
                                        </label>
                                        <input type="number" value="0" min="0"  name="final_price" id="final_price" class="form-control">
                                        @error('final_price')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Price For User: <span class="required">*</span>
                                        </label>
                                        <input type="number" value="0" min="0"  name="price_for_user" id="price_for_user" class="form-control">
                                        
                                        @error('price_for_user')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Date Of Purchase: <span class="required">*</span>
                                        </label>
                                        <input type="date" name="date_of_purchase" id="date_of_purchase" class="form-control">
                                        @error('date_of_purchase')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Select Vendor: <span class="required">*</span>
                                        </label>
                                        <select name="vendor_id" class="form-control select2" id="vendor_id">
                                            <option>Select Vendor</option>
                                            @foreach($vendor as $v_id => $v)
                                                <option value="{{$v->id}}">{{$v->name}}</option>
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
                                            Description: 
                                        </label>
                                        <textarea name="description" class="form-control" id="description"></textarea>
                                        @error('description')
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