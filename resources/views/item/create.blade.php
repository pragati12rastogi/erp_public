@if(Auth::user()->hasPermissionTo('item.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="item_add_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Item</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="item_form" method="post" enctype="multipart/form-data" action="{{url('item')}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Item Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" type="text" maxlength="255" class="form-control text-capitalize" >
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
                                            Category: <span class="required">*</span>
                                        </label>
                                        <select class="form-control select2" name="category_id" >
                                            <option value="">Select Category</option>
                                            @foreach($categories as $ind => $cat)
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
                                            HSN: <span class="required">*</span>
                                        </label>
                                        <select class="form-control select2" name="hsn_id" >
                                            <option value="">Select Hsn</option>
                                            @foreach($hsns as $ind => $hsn)
                                                <option value="{{$hsn->id}}">{{$hsn->hsn_no}}</option>
                                            @endforeach
                                        </select>
                                        @error('hsn_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            GST: <span class="required">*</span>
                                        </label>
                                        <select class="form-control select2" name="gst_percent_id" >
                                            <option value="">Select GST</option>
                                            @foreach($gsts as $ind => $gst)
                                                <option value="{{$gst->id}}">{{$gst->percent}} %</option>
                                            @endforeach
                                        </select>
                                        @error('gst_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="address">
                                            Image: 
                                        </label>
                                        <br>
                                        <input type="file" id="photo" name="photo[]" accept="image/*" multiple>
                                        <br>
                                        <small class="txt-desc">(Photo accept jpeg,png and jpg)</small>
                                        @error('photo')
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