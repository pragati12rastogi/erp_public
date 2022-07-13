@if(Auth::user()->hasPermissionTo('states.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="state_edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit State</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="state_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            State Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" id="state_name_upd" type="text" maxlength="255" value="" class="form-control text-capitalize"  >
                                        
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