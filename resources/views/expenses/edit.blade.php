@if(Auth::user()->hasPermissionTo('expenses.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="expense_edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Expense</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="expense_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Date Time : <span class="required">*</span>
                                        </label>
                                        <input name="datetime" id="datetime_upd" type="datetime-local" maxlength="255" class="form-control " >
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
                                            Description : 
                                        </label>
                                        <textarea name="description" id="description_upd" class="form-control" ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row expense_count_upd div_tocopy_upd">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Expense Name: <span class="required">*</span>
                                        </label>
                                        <select name="name[]" id="name_0_upd" class="expname_upd form-control select2">
                                            <option value=""> Select Expense </option>
                                            @foreach($masters as $mid => $master )
                                            <option value="{{$master->id}}"> {{$master->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Expense Amount: <span class="required">*</span>
                                        </label>
                                        <input name="type_amount[]" id="type_amount_0_upd" min="0" type="number" class="form-control " >
                                    </div>
                                </div>
                            </div>
                            <div class="replicate_div_upd">
                                
                            </div>
                            <div class="mb-3">
                                <button type="button" onclick="addOtherFn('_upd')" class="exp_addOther btn btn-outline-danger btn-sm"><i class="mdi mdi-plus"></i>Add More</button>
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