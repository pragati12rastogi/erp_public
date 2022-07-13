@if(Auth::user()->hasPermissionTo('item.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="{{$item->id}}_item_edit_modal" class="modal fade" role="dialog">
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
                        <form id="item_form_{{$item->id}}" method="post" enctype="multipart/form-data" action="{{url('item/'.$item->id)}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Item Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" value="{{$item->name}}" type="text" maxlength="255" class="form-control text-capitalize" >
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
                                                <option value="{{$cat->id}}" {{($item->category_id == $cat->id)?'selected':''}}>{{$cat->name}}</option>
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
                                                <option value="{{$hsn->id}}" {{($item->hsn_id == $hsn->id)?'selected':''}}>{{$hsn->hsn_no}}</option>
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
                                                <option value="{{$gst->id}}" {{($item->gst_percent_id == $gst->id)?'selected':''}}>{{$gst->percent}} %</option>
                                            @endforeach
                                        </select>
                                        @error('gst_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 ">
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
                                <div class="col-md-12">
                                    <div class="row">
                                        
                                        @foreach($item->images as $i => $img )
                                        @if($img->photo != '' && file_exists(public_path().'/uploads/items/'.$img->photo))
                                                
                                            <div class="col-md-3  col-sm-6">
                                                
                                                <div class="card-inverse-secondary ">
                                                    <center>
                                                        <img class="img-thumbnail" src=" {{url('/uploads/items/'.$img->photo)}}">
                                                    </center>  
                                                    
                                                </div>
                                                <a href="{{route('delete.item.photo',$img->id)}}" class="mdi mdi-delete-circle-outline text-danger other_image_delete_style"></a>
                                            </div>
                                        @endif
                                        @endforeach
                                    </div>
                                    
                                    
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                
                                <button type="submit" class="btn btn-dark">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif