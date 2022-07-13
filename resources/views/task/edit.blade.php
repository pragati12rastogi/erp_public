@if(Auth::user()->hasPermissionTo('tasks.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="edit_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Task</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="task_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" id="name_upd" type="text" maxlength="255" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Description: 
                                        </label>
                                        <textarea name="description" id="description_upd" cols="10" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Start Date: <span class="required">*</span>
                                        </label>
                                        <input name="start_at" id="start_at_upd" type="date" maxlength="255" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Due Date: 
                                        </label>
                                        <input name="end_at" id="end_at_upd" type="date" maxlength="255" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Priority: <span class="required">*</span>
                                        </label>
                                        <select name="priority"id="priority_upd" class="form-control select2">
                                            <option value="Medium">Medium</option>
                                            <option value="High">High</option>
                                            <option value="Urgent">Urgent</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Assigned To: <span class="required">*</span>
                                        </label>
                                        <select name="assigned_to[]" id="assigned_to_upd" data-placeholder="Select Users" class="form-control select2"  multiple>
                                            @foreach($users as $usind => $us)
                                            <option value="{{$us->id}}">{{$us->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="javascript:void(0);" onclick="add_checklist('_upd')" class="control-label col-md-12" for="checklist_item">
                                            <span class="mdi mdi-plus"></span> Checklist Item
                                        </a>
                                    </div>
                                    <div id="append-more-checklist_upd" ></div>
                                </div>
                                <input type="hidden" value="0" id="upd_task_id" class="form-control">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="attachment">
                                            Upload Attachment :
                                        </label>
                                        <input type="file" id="prod_model_file" class="image-uploader" multiple>
                                    </div>
                                </div>

                            </div>
                            <div id="append_after_list" class="row">
                                
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