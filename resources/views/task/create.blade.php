@if(Auth::user()->hasPermissionTo('tasks.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="add_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Task</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="task_form" method="post" enctype="multipart/form-data" action="{{url('tasks')}}" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}
                            
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Name: <span class="required">*</span>
                                        </label>
                                        <input name="name" type="text" maxlength="255" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Description: 
                                        </label>
                                        <textarea name="description" cols="10" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Start Date: <span class="required">*</span>
                                        </label>
                                        <input name="start_at" type="date" maxlength="255" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Due Date: 
                                        </label>
                                        <input name="end_at" type="date" maxlength="255" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Priority: <span class="required">*</span>
                                        </label>
                                        <select name="priority" class="form-control select2">
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
                                        <select name="assigned_to[]" data-placeholder="Select Users" class="form-control select2"  multiple>
                                            @foreach($users as $usind => $us)
                                            <option value="{{$us->id}}">{{$us->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-12" for="attachment">
                                            Attachment :
                                        </label>
                                        <div class="input-group">
                                            <input type="file" name="task_attachment[]" class="form-control">
                                            <div class="input-group-prepend">
                                                <button type="button" onclick="add_more_attachment()" class="input-group-text mdi mdi-plus text-black bg-inverse-info" ></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="append-more-attachment" class="form-group ">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="javascript:void(0);" onclick="add_checklist()" class="control-label col-md-12" for="checklist_item">
                                            <span class="mdi mdi-plus"></span> Checklist Item
                                        </a>
                                    </div>
                                    <div id="append-more-checklist">
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