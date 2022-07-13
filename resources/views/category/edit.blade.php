@if(Auth::user()->hasPermissionTo('category.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="{{$cat->id}}_category_edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Category</h4>
                <button type="button" class="close m-0 p-0" data-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="category_form_{{$cat->id}}" method="post" enctype="multipart/form-data" action="{{url('category/'.$cat->id)}}" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Category Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" type="text" maxlength="255" value="{{$cat->name}}" class="form-control text-capitalize" placeholder="Category 1" >
                                        @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="control-label" for="address">
                                                    Image: 
                                                </label>
                                                <br>
                                                <input type="file" id="photo" name="photo" accept="image/*">
                                                <br>
                                                <small class="txt-desc">(Photo accept jpeg,png and jpg)</small>
                                                @error('photo')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="card-inverse-secondary ">
                                                <center>
                                                    @if($cat->image != '' && file_exists(public_path().'/uploads/category/'.$cat->image))
                                                        <img class="img-avatar" src=" {{url('/uploads/category/'.$cat->image)}}">
                                                    @endif
                                                </center>    
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-dark ">Update</button> 
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