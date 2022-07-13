@if(Auth::user()->hasPermissionTo('gst.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="gst_edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit GST</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="gst_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            GST Percentage: <span class="required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input name="percent" id="percent_upd" min="0" type="number" value="" maxlength="255" class="form-control" >
                                            <div class="input-group-append">
                                                <span class="input-group-text ">
                                                <i class="mdi mdi-percent"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @error('percent')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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