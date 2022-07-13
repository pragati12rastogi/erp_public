@if(Auth::user()->hasPermissionTo('mytasks.view') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="view_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading" id="name_upd"></h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body pt-0">
                <div class="row">
                    <div class="col-md-9 pt-3">
                        <form id="task_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}
                            {{ method_field('PUT') }}
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Description: 
                                        </label>
                                        <p id="description_upd" ></p>
                                    </div>
                                </div>
                                <hr>
                                <h4>Checklist</h4>
                                <div class="col-md-12">
                                    <div id="append-more-checklist_upd" ></div>
                                </div>
                                <input type="hidden" value="0" id="upd_task_id" class="form-control">
                            
                            </div>
                            <hr>
                            <h4>Attachments</h4>
                            <div id="append_after_list" class="row">
                                
                            </div>
                            <br>
                            
                            <div class="modal-footer">  
                                <button type="reset" class="btn btn-inverse-dark" data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3 pt-3 border-5 border-left-sm bg-inverse-primary">
                        <div class="row">
                            <h4>Task Info</h4>
                            <small>Created by <b id="task_created_by"></b> <span class="mdi mdi-clock" id="task_created_at" title=""></span></small>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2 my-auto text-center"><i class="mdi mdi-star-half"></i></div>
                                    <div class="col-md-10 d-flex text-small">
                                        <label class="my-auto mx-2" >Status: </label> 
                                        <select id="upd_status" class="">
                                            @foreach($status_array as $sind => $st)
                                            <option value="{{$st}}">{{$st}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 my-auto text-center"><i class="mdi mdi-calendar"></i></div>
                                    <div class="col-md-10 d-flex text-small">
                                        <label class="my-auto mx-2" >Start Date: </label> 
                                        <b id="start_at_upd" class="my-auto"></b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 my-auto text-center"><i class="mdi mdi-calendar-check"></i></div>
                                    <div class="col-md-10 d-flex text-small">
                                        <label class="my-auto mx-2" >End Date: </label> 
                                        <b id="end_at_upd" class="my-auto"></b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 my-auto text-center"><i class="mdi mdi-rocket"></i></div>
                                    <div class="col-md-10 d-flex text-small">
                                        <label class="my-auto mx-2" >Priority: </label> 
                                        <b id="priority_upd" class="my-auto"></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h4> <u>Assignees</u> </h4>
                            </div>
                            <div class="col-md-12">
                                <div id="assignees_append" class="row"></div>
                            </div>
                            
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-md-12" for="attachment">
                                        Upload Attachment :
                                    </label>
                                    <input type="file" id="prod_model_file" class="image-uploader" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif