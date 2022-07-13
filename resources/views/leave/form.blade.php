@if(Auth::user()->hasPermissionTo('leave.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
<div id="add_leave_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Apply for Leave</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="leave_form" method="post" enctype="multipart/form-data" action="{{url('leave')}}" data-parsley-validate class="form-horizontal form-label-left validateForm">
                          {{csrf_field()}}
                          
                            <div class="row">

                                @if(Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            User Name: <span class="required">*</span>
                                        </label>
                                        <select name="user_id" data-placeholder="Select User" class="form-control select2">
                                            @foreach($users as $ind => $us)
                                                <option value="{{$us->id}}">{{$us->name}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                                @else
                                <input type="hidden" name="user_id" value="{{Auth::id()}}">
                                @endif

                                <div class="col-md-12">
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            From Date: <span class="required">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="date" name="from_date" id="from_date" class="form-control " />
                                            <div class="input-group-text">
                                                <div class=" input-group-append" >
                                                    <span>To</span>
                                                </div>
                                            </div>
                                            <input autocomplete="off" type="date" name="to_date" id="to_date" class="form-control " onchange="validateDate()" />
                                        </div>
                                        <span id="date_error" class="error text-small" ></span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Reason: <span class="required">*</span>
                                        </label>
                                        <textarea type="text" name="reason" class="form-control"></textarea>
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

