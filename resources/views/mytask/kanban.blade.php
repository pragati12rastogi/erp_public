@extends('layouts.master')
@section('title', 'My Tasks Kanban')

@push('style')

<style>
    input#prod_model_file {
        display: inline-block;
        width: 100%;
        padding: 100px 0 0 0;
        height: 30px;
        overflow: hidden;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background: url('{{asset("images/698394.png")}}') center center no-repeat #e4e4e4;
        border-radius: 20px;
        background-size: 60px 60px;
    }

    .prod_upload_files{
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background:#e4e4e4;
        border-radius: 5px;
    }

    .kanban-float-left {
        float:left;
    }

    .type-width{
        width: 20rem;
    }

    .kanban-type-row{
        overflow-x: auto;
        width: 1100px;
        min-height: 500px;
        display: flex;
    }

    [data-draggable="target"]
    {
        float:left;
        list-style-type:none;
        
        width:100%;
        height:30.5em;
        overflow:auto;
        padding:10px;
        /* margin:0 0.5em 0.5em 0;*/
         
    }

    [data-draggable="item"]
    {
        display:block;
        list-style-type:none;
        
       /*  margin:0 0 2px 0;
        padding:0.2em 0.4em; */
        
        border-radius:0.2em;
        
        line-height:1.3;
    }

    .img-xxs{
        width: 25px;
        height: 25px;
    }

    .alert-heading{
        cursor:pointer;
    }
</style>
@endpush

@push('custom-scripts')
<script src="{{asset('/js/image-file-uploader.js')}}"></script>
<script src="{{asset('/js/task.js')}}"></script>
    <script>
        
        function view_modal(edit_id){
            var submit_edit_url = '{{url("mytasks")}}/'+edit_id;
            
            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:submit_edit_url,
                success:function(result){

                    if(result != ''){
                        var tasks = result.task;
                        var attachments = result.attachment;
                        var checklists = result.checklist;
                        var assignees_user = result.assignees_user;
                        $('#task_form_upd').attr('action',submit_edit_url);
                        $('#name_upd').text(tasks.name);
                        $('#description_upd').text(tasks.description);
                        $("#task_created_by").text(tasks.created_by_user.name);
                        $("#task_created_at").attr('title',result.created_date);
                        $("#upd_status").attr("onchange","updateTaskStatus(this,"+tasks.id+")");
                        $("#upd_status").val(tasks.status).trigger('change');
                        $('#start_at_upd').text(tasks.start_at);
                        $('#end_at_upd').text(tasks.end_at);
                        $('#priority_upd').text(tasks.priority);
                        var assignees_append = '';
                        $("#assignees_append").empty();
                        $.each(assignees_user,function(assind,assval){
                            var image = "{{asset('images/no-image.jpg')}}";
                            if(assval.profile != null && assval.profile != ''){
                                image = "{{url('/uploads/user_profile/')}}/"+assval.profile;
                            }
                            assignees_append += '<div class="col-md-2 p-0 my-auto text-center">'+
                                '<img src="'+image+'" class="img-sm img-thumbnail rounded-circle" title="'+assval.name+'" alt="'+assval.name+'">'+
                            '</div>' ;
                        });
                        $("#assignees_append").append(assignees_append);
                        $("#upd_task_id").val(tasks.id);
                        
                        $("#append_after_list").empty();
                        var append_attachment = '';
                        $.each(attachments,function(atind,atval){
                            var split_file_type = (atval.file_type).split('/');
                            var split_path = (atval.path).split('/');
                            var cross_btn ='';
                            if(atval.created_by == "{{Auth::id()}}"){
                                cross_btn = '<div class="col-md-3">'+
                                                '<button type="button" class="btn-close" onclick="delete_attachment('+atval.id+',this)" ></button>'+
                                            '</div>';
                            }
                            if(split_file_type[0] == 'image'){
                                
                                append_attachment += '<div class="col-md-3 upd_appended_attachment">'+
                                    '<div class="card prod_upload_files">'+
                                        '<div class="card-header text-small">'+
                                            '<div class="row">'+
                                                '<div class="col-md-9">'+(atval.file_type).toUpperCase()+'</div>'+
                                                cross_btn +
                                            '</div>'+
                                            '<div class="row"><small>By-'+atval.created_by_user.name+'</small></div>'+
                                        '</div>'+
                                        '<div class="card-body text-center p-3">'+
                                            '<div class="row"><div class="col-md-12">'+
                                            '<a href="'+atval.path+'" download><img src="'+atval.path+'" class="img-thumbnail"></a>'+
                                            '</div></div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                            }else{
                                append_attachment += '<div class="col-md-3 upd_appended_attachment">'+
                                    '<div class="card prod_upload_files">'+
                                        '<div class="card-header text-small">'+
                                            '<div class="row">'+
                                                '<div class="col-md-9">'+(atval.file_type).toUpperCase()+'</div>'+
                                                cross_btn +
                                            '</div>'+
                                            '<div class="row"><small>By-'+atval.created_by_user.name+'</small></div>'+
                                        '</div>'+
                                        '<div class="card-body text-center p-3">'+
                                            '<span class="mdi mdi-file-document-outline"></span>'+
                                            '<a href="'+atval.path+'" download class="imageThumb">'+split_path[2]+'</a>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                            }
                        })
                        $("#append_after_list").append(append_attachment);

                        $("#append-more-checklist_upd").empty();
                        var append_checklist = '';

                        $.each(checklists,function(checkind, checkval){
                            var check_select = '';
                            var check_completed_by = '';
                            if(checkval.completed_by != null && checkval.completed_by != ''){
                                check_select = 'checked';
                                check_completed_by = ' - Completed by '+checkval.completed_by_user.name;
                            }
                            append_checklist += '<div class="appended-checklist-content form-check">'+
                                '<div class="input-group">'+
                                    '<label class="inline-checkbox">'+
                                    '<input type="checkbox" class="my-auto m-1" value="1" onclick="mark_checklist('+checkval.id+')" '+check_select+'>'+
                                    checkval.checklist+'</label>'+
                                '</div>'+
                                '<span class="text-small"> Created by '+checkval.created_by_user.name+' '+ check_completed_by+'</span>'+
                            '</div>';
                        })
                        $("#append-more-checklist_upd").append(append_checklist);
                        $("#view_modal").modal('show');
                    }else{
                        alert('some error occured, please refresh page and try again');
                    }

                },
                error:function(error){
                    console.log(error.responseText);
                }
            })
        }

(function()
{

  //exclude older browsers by the features we need them to support
  //and legacy opera explicitly so we don't waste time on a dead browser
  if
  (
    !document.querySelectorAll 
    || 
    !('draggable' in document.createElement('span')) 
    || 
    window.opera
  ) 
  { return; }
  
  //get the collection of draggable items and add their draggable attribute
  for(var 
    items = document.querySelectorAll('[data-draggable="item"]'), 
    len = items.length, 
    i = 0; i < len; i ++)
  {
    items[i].setAttribute('draggable', 'true');
  }

  for(var 
    targets = document.querySelectorAll('[data-draggable="target"]'), 
    len_t = targets.length, 
    t = 0; t < len_t; t ++)
  {
    var id_check = targets[t].getAttribute('id');
    if(targets[t].children.length == 0){
        $('#'+id_check).append('<div class="notask text-center"><i class="h1 mdi mdi-redo-variant"></i><br><span>No Task</span></div>')
    }else{
        $('#'+id_check+ ' .notask').remove();
    }
  }
  //variable for storing the dragging item reference 
  //this will avoid the need to define any transfer data 
  //which means that the elements don't need to have IDs 
  var item = null;

  //dragstart event to initiate mouse dragging
  document.addEventListener('dragstart', function(e)
  {
    //set the item reference to this element
    item = e.target;
    
    //we don't need the transfer data, but we have to define something
    //otherwise the drop action won't work at all in firefox
    //most browsers support the proper mime-type syntax, eg. "text/plain"
    //but we have to use this incorrect syntax for the benefit of IE10+
    e.dataTransfer.setData('text', '');
  
  }, false);

  //dragover event to allow the drag by preventing its default
  //ie. the default action of an element is not to allow dragging 
  document.addEventListener('dragover', function(e)
  {
    if(item)
    {
      e.preventDefault();
    }
  
  }, false);  

  //drop event to allow the element to be dropped into valid targets
  document.addEventListener('drop', function(e)
  {         
    //if this element is a drop target, move the item here 
    //then prevent default to allow the action (same as dragover)
    var drag_from = (item.parentElement).getAttribute('id');
    var drag_child_length = item.parentElement.children.length;
    if((parseInt(drag_child_length)-1)<=0){
        $('#'+drag_from).append('<div class="notask text-center"><i class="h1 mdi mdi-redo-variant"></i><br><span>No Task</span></div>')
    }else{
        $('#'+drag_from+ ' .notask').remove();
    }
    if(e.target.getAttribute('data-draggable') == 'target')
    { 
        e.target.appendChild(item);
        var div = (e.target).getAttribute('id');
        var moved_ele = (item).getAttribute('id');
        $.ajax({
            type: 'GET',
            dataType:'JSON',
            data:{'moved_to':div,'moved_ele':moved_ele},
            url: "{{url('update-status/kanban')}}",
            success: function(response){
                
                $('#'+div+ ' .notask').remove();
            }
        });
        e.preventDefault();
    }
  
  }, false);
  
  //dragend event to clean-up after drop or abort
  //which fires whether or not the drop target was valid
  document.addEventListener('dragend', function(e)
  {
    item = null;
  
  }, false);

})(); 
    </script>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 ">
      
        @include('flash-msg')
      
    </div>
</div>
@php
    $status_array = ['In Progress','Not Started','Testing','Awaiting Feedback','Complete'];
@endphp
<div class="row">
  <div class="col-lg-12 ">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-5" >
                
                <a href="{{url('mytasks')}}" class="btn btn-inverse-primary btn-sm d-inline-block mb-2">{{__("Switch To List")}}</a>
            </div>
            
        </div>
        <div class="kanban-type-row">
            
            <div class="kanban-float-left" >
                <div class="card text-white bg-secondary m-3 type-width">
                    <div class="card-header bg-github">Not Started</div>
                    <div class=" ">
                        <ol data-draggable="target" id="not_started">
                            @foreach($not_started as $key => $h)
                            <li data-draggable="item" id="not_started_{{$h->id}}">
                                @php $li_color = "alert-success border-success"; 
                                if(!empty($h->end_at)){
                                    if(strtotime($h->end_at) < strtotime(date('Y-m-d'))){
                                        $li_color = "alert-danger border-danger";
                                    }
                                }
                                $assigned_name = App\Models\User::whereIn('id',$h->assigned_to)->get();
                                @endphp
                                <div class="alert {{$li_color}}" role="alert" >
                                    <a  <?php  if(Auth::user()->hasPermissionTo('mytasks.view') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN)) { ?> onclick='view_modal("{{$h->id}}")' <?php }?> ><b class="alert-heading">{{$h->name}}</b></a><br>
                                    <span class="text-muted"># {{$h->priority}}</span>
                                    <div class="row mt-2">
                                        <div class="col-md-8">
                                            @foreach($assigned_name as $as_in => $as_image)
                                                
                                                <div class="col-md-1 p-0 my-auto text-center d-inline">
                                                @if(!empty($as_image->profile) && file_exists(public_path().'/uploads/user_profile/'.$as_image->profile))
                                                    <img src="{{url('/uploads/user_profile/'.$as_image->profile)}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @else
                                                    <img src="{{asset('images/no-image.jpg')}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @endif
                                                </div>

                                            @endforeach
                                        </div>
                                        <div class="col-md-4">
                                            <span class="mdi mdi-check-decagram"></span>
                                            <span>{{count($h->done_checklist)}}/{{count($h->checklists)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
            <div class="kanban-float-left">
                <div class="card text-white bg-primary m-3 type-width" >
                    <div class="card-header bg-facebook">In Progress</div>
                    <div class="bg-secondary">
                        <ol data-draggable="target" id="in_progress">
                        @foreach($in_progress as $key => $h)
                            <li data-draggable="item" id="not_started_{{$h->id}}">
                                @php $li_color = "alert-success border-success"; 
                                if(!empty($h->end_at)){
                                    if(strtotime($h->end_at) < strtotime(date('Y-m-d'))){
                                        $li_color = "alert-danger border-danger";
                                    }
                                }
                                $assigned_name = App\Models\User::whereIn('id',$h->assigned_to)->get();
                                @endphp
                                <div class="alert {{$li_color}}" role="alert">
                                    <a <?php  if(Auth::user()->hasPermissionTo('mytasks.view') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN)) { ?> onclick='view_modal("{{$h->id}}")' <?php }?> ><b class="alert-heading">{{$h->name}}</b></a><br>
                                    <span class="text-muted"># {{$h->priority}}</span>
                                    <div class="row mt-2">
                                        <div class="col-md-8">
                                            @foreach($assigned_name as $as_in => $as_image)
                                                
                                                <div class="col-md-1 p-0 my-auto text-center d-inline">
                                                @if(!empty($as_image->profile) && file_exists(public_path().'/uploads/user_profile/'.$as_image->profile))
                                                    <img src="{{url('/uploads/user_profile/'.$as_image->profile)}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @else
                                                    <img src="{{asset('images/no-image.jpg')}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @endif
                                                </div>

                                            @endforeach
                                        </div>
                                        <div class="col-md-4">
                                            <span class="mdi mdi-check-decagram"></span>
                                            <span>{{count($h->done_checklist)}}/{{count($h->checklists)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        </ol>
                    </div>
                </div>
            </div>
            <div class="kanban-float-left">
                <div class="card text-white bg-warning m-3 type-width">
                    <div class="card-header">Testing</div>
                    <div class="bg-secondary">
                        <ol data-draggable="target" id="testing">
                        @foreach($testing as $key => $h)
                            <li data-draggable="item" id="testing_{{$h->id}}">
                                @php $li_color = "alert-success border-success"; 
                                if(!empty($h->end_at)){
                                    if(strtotime($h->end_at) < strtotime(date('Y-m-d'))){
                                        $li_color = "alert-danger border-danger";
                                    }
                                }
                                $assigned_name = App\Models\User::whereIn('id',$h->assigned_to)->get();
                                @endphp
                                <div class="alert {{$li_color}}" role="alert">
                                    <a <?php  if(Auth::user()->hasPermissionTo('mytasks.view') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN)) { ?> onclick='view_modal("{{$h->id}}")' <?php }?> ><b class="alert-heading">{{$h->name}}</b></a><br>
                                    <span class="text-muted"># {{$h->priority}}</span>
                                    <div class="row mt-2">
                                        <div class="col-md-8">
                                            @foreach($assigned_name as $as_in => $as_image)
                                                
                                                <div class="col-md-1 p-0 my-auto text-center d-inline">
                                                @if(!empty($as_image->profile) && file_exists(public_path().'/uploads/user_profile/'.$as_image->profile))
                                                    <img src="{{url('/uploads/user_profile/'.$as_image->profile)}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @else
                                                    <img src="{{asset('images/no-image.jpg')}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @endif
                                                </div>

                                            @endforeach
                                        </div>
                                        <div class="col-md-4">
                                            <span class="mdi mdi-check-decagram"></span>
                                            <span>{{count($h->done_checklist)}}/{{count($h->checklists)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        </ol>
                    </div>
                </div>
            </div> 
            <div class="kanban-float-left">
                <div class="card text-white bg-danger m-3 type-width" >
                    <div class="card-header">Awaiting Feedback</div>
                    <div class="bg-secondary">
                        <ol data-draggable="target" id="awaiting_feedback">
                        @foreach($awaiting_feedback as $key => $h)
                            <li data-draggable="item" id="awaiting_feedback_{{$h->id}}">
                                @php $li_color = "alert-success border-success"; 
                                if(!empty($h->end_at)){
                                    if(strtotime($h->end_at) < strtotime(date('Y-m-d'))){
                                        $li_color = "alert-danger border-danger";
                                    }
                                }
                                $assigned_name = App\Models\User::whereIn('id',$h->assigned_to)->get();
                                @endphp
                                <div class="alert {{$li_color}}" role="alert">
                                    <a <?php  if(Auth::user()->hasPermissionTo('mytasks.view') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN)) { ?> onclick='view_modal("{{$h->id}}")' <?php }?> ><b class="alert-heading">{{$h->name}}</b></a><br>
                                    <span class="text-muted"># {{$h->priority}}</span>
                                    <div class="row mt-2">
                                        <div class="col-md-8">
                                            @foreach($assigned_name as $as_in => $as_image)
                                                
                                                <div class="col-md-1 p-0 my-auto text-center d-inline">
                                                @if(!empty($as_image->profile) && file_exists(public_path().'/uploads/user_profile/'.$as_image->profile))
                                                    <img src="{{url('/uploads/user_profile/'.$as_image->profile)}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @else
                                                    <img src="{{asset('images/no-image.jpg')}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @endif
                                                </div>

                                            @endforeach
                                        </div>
                                        <div class="col-md-4">
                                            <span class="mdi mdi-check-decagram"></span>
                                            <span>{{count($h->done_checklist)}}/{{count($h->checklists)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        </ol>
                    </div>
                </div>
            </div> 
            <div class="kanban-float-left">
                <div class="card text-white bg-success m-3 type-width">
                    <div class="card-header">Completed</div>
                    <div class="bg-secondary">
                        <ol data-draggable="target" id="complete">
                        @foreach($complete as $key => $h)
                            <li data-draggable="item">
                                @php $li_color = "alert-success border-success"; 
                                if(!empty($h->end_at)){
                                    if(strtotime($h->end_at) < strtotime(date('Y-m-d'))){
                                        $li_color = "alert-danger border-danger";
                                    }
                                }
                                $assigned_name = App\Models\User::whereIn('id',$h->assigned_to)->get();
                                @endphp
                                <div class="alert {{$li_color}}" role="alert">
                                    <a <?php  if(Auth::user()->hasPermissionTo('mytasks.view') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN)) { ?> onclick='view_modal("{{$h->id}}")' <?php }?> ><b class="alert-heading">{{$h->name}}</b></a><br>
                                    <span class="text-muted"># {{$h->priority}}</span>
                                    <div class="row mt-2">
                                        <div class="col-md-8">
                                            @foreach($assigned_name as $as_in => $as_image)
                                                
                                                <div class="col-md-1 p-0 my-auto text-center d-inline">
                                                @if(!empty($as_image->profile) && file_exists(public_path().'/uploads/user_profile/'.$as_image->profile))
                                                    <img src="{{url('/uploads/user_profile/'.$as_image->profile)}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @else
                                                    <img src="{{asset('images/no-image.jpg')}}" class="img-xxs img-thumbnail rounded-circle" title="{{$as_image->name}}" alt ="{{$as_image->name}}">
                                                @endif
                                                </div>

                                            @endforeach
                                        </div>
                                        <div class="col-md-4">
                                            <span class="mdi mdi-check-decagram"></span>
                                            <span>{{count($h->done_checklist)}}/{{count($h->checklists)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        </ol>
                    </div>
                </div>
            </div>  
        </div>
      </div>
    </div>
  </div>
</div>

@include('mytask.view')

@endsection