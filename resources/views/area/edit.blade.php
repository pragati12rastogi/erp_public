@if(Auth::user()->hasPermissionTo('areas.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
<div id="area_edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Area</h4>
                <button type="button" class="close m-0 p-0" data-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="area_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left">
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
                                        <select name="district_id" id="district_id_upd" class="form-control select2">
                                            <option value="">Select District</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Area Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" id="name_upd" type="text" maxlength="255" value="" class="form-control text-capitalize"  >
                                        
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