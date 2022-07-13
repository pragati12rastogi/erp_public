@if(Auth::user()->hasPermissionTo('hsn.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="add_hsn_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add HSN</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                      <form id="hsn_form" method="post" enctype="multipart/form-data" action="{{url('hsn')}}" data-parsley-validate class="form-horizontal form-label-left">
                          {{csrf_field()}}
                          
                          <div class="row">
                              
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label class="control-label" for="first-name">
                                          Hsn Number: <span class="required">*</span>
                                      </label>
                                      <input name="hsn_no" type="text" maxlength="255" class="form-control text-capitalize" >
                                      @error('hsn_no')
                                          <span class="invalid-feedback d-block" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
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