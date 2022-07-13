@extends('layouts.master')
@section('title', 'Calendar')

@push('style')
{!! Html::style('/plugins/fullcalendar/main.min.css')!!}
@endpush

@push('custom-scripts')
    {!! Html::script('/plugins/fullcalendar/main.min.js') !!}
    {!! $calendar->script() !!}
    <script>
        $(function() {

            
            $('form.validateForm').each(function(key, form) {
                jQuery(form).validate({
                    // initialize the plugin
                    rules: {

                        title:{
                            required:true,
                        },
                        start_date:{
                            required:true,
                        }
                        
                    },
                    errorPlacement: function(error,element)
                    {
                        if($(element).attr('type') == 'radio')
                        {
                            error.insertAfter(element.parent());
                        }
                        else if($(element).is('select'))
                        {
                            error.insertAfter(element.parent());
                        }
                        else{
                            error.insertAfter(element);
                        }
                            
                    }
                });
            });

            
        });
        
        function editModal(title,url,description,start_date,end_date,event_color,public_event){
            @if(Auth::user()->hasPermissionTo('calendar.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            $("#title_upd").val(title);
            $("#description_upd").val(description);
            var start_now = new Date(start_date);
            start_now.setMinutes(start_now.getMinutes() - start_now.getTimezoneOffset());
            $("#start_date_upd").val(start_now.toISOString().slice(0,16));

            var end_now = new Date(end_date);
            end_now.setMinutes(end_now.getMinutes() - end_now.getTimezoneOffset());
            $("#end_date_upd").val(end_now.toISOString().slice(0,16));

            $("#event_color_upd").val(event_color);
            if(public_event == 1){
                $("#public_event_upd").attr('checked',true);
            }
            $("#calendar_form_upd").attr('action',url);
            $("#delete_form").attr('action',url);
            $('#calendarEditModal').modal();
            @endif
            
        }


        function addModal(){
            @if(Auth::user()->hasPermissionTo('calendar.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
                $("#calendarAddModal").modal('show');
            @endif
        }
        
    </script>
@endpush

@section('content')
<div class="row"><div class="col-md-12 ">@include('flash-msg')</div></div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="border-bottom mb-3 row">
                    <div class="col-md-10">
                        <h4 class="card-title">Attendance Summary</h4>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! $calendar->calendar() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="calendarEditModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Edit Event</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div id="modalBody" class="modal-body"> 
                <form id="calendar_form_upd" method="post" enctype="multipart/form-data" action="" data-parsley-validate class="form-horizontal form-label-left validateForm">
                    {{csrf_field()}}
                    {{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="first-name">
                                    Event Title: <span class="required">*</span>
                                </label>
                                <input type="text" id="title_upd" class="form-control" name="title">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="first-name">
                                    Description: 
                                </label>
                                <textarea name="description" id="description_upd" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="first-name">
                                    Start Date: <span class="required">*</span>
                                </label>
                                <input name="start_date" id="start_date_upd" type="datetime-local" maxlength="255" value="" class="form-control text-capitalize" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="first-name">
                                    End Date: <span class="required">*</span>
                                </label>
                                <input name="end_date" id="end_date_upd" type="datetime-local" maxlength="255" value="" class="form-control text-capitalize" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="first-name">
                                    Event Color: 
                                </label>
                                <input type="color" id="event_color_upd" name="event_color" class="form-control input-group-text"  value="#f05050">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="first-name">
                                <input name="public_event" id="public_event_upd" type="checkbox"  value="1" >
                                    Public Event
                                </label>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark btn-sm ">Save</button>  
                </form>  
                        <button type="reset" class="btn btn-inverse-dark btn-sm" data-dismiss="modal">Cancel</button>
                        <form method="post" id="delete_form" action="" class="pull-right">
                            {{csrf_field()}}
                            {{method_field("DELETE")}}
                    
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>  
                    </div>
                
            </div>
        </div>
    </div>
</div>

<div id="calendarAddModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content bg-inverse-light">
            <div class="modal-header">
                <h4 class="modal-heading">Add Event</h4>
                <button type="button" class="btn-close m-0 p-0" data-dismiss="modal"></button> 
            </div>
            <div id="modalBody" class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <form id="calendar_form" method="post" enctype="multipart/form-data" action="{{url('calendar')}}" data-parsley-validate class="form-horizontal form-label-left validateForm">
                            {{csrf_field()}}
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Event Title: <span class="required">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="title">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Description: 
                                        </label>
                                        <textarea name="description" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Start Date: <span class="required">*</span>
                                        </label>
                                        <input name="start_date" type="datetime-local" maxlength="255" value="" class="form-control text-capitalize" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            End Date: 
                                        </label>
                                        <input name="end_date" type="datetime-local" maxlength="255" value="" class="form-control text-capitalize" >
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                            Event Color: 
                                        </label>
                                        <input type="color" name="event_color" class="form-control input-group-text"  value="#f05050">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="first-name">
                                        <input name="public_event" type="checkbox"  value="1" >
                                            Public Event
                                        </label>
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

@endsection