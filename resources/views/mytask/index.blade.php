@extends('layouts.master')
@section('title', 'My Tasks Summary')

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
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">My Tasks Summary</h4>
            </div>
            <div class="col-md-2" >
              <a href="{{route('kanban.mytasks')}}" class="btn btn-inverse-primary btn-sm">{{__("Switch To Kanban")}}</a>
            </div>
        </div>
        
        <div class="table-responsive">
            <table id="task_table" class="table ">
                <thead>
                    <tr>
                        <th>Sr.no.</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Assignsed To</th>
                        <th>Priority</th>
                        @if(Auth::user()->hasPermissionTo('mytasks.view') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $key => $h)
                        @php
                            $assigned_name = App\Models\User::whereIn('id',$h->assigned_to)->select(DB::raw('GROUP_CONCAT(name) as name'))->first()->name;
                        @endphp 
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$h->name}}</td>
                        <td>
                            <select onchange="updateTaskStatus(this,'{{$h->id}}')" class="form-control select2">
                                @foreach($status_array as $sind => $st)
                                <option value="{{$st}}" {{ ($h->status==$st) ? 'selected' : '' }}>{{$st}}</option>
                                @endforeach
                            </select> 
                        </td>
                        
                        <td>{{date('d-m-Y',strtotime($h->start_at))}}</td>
                        <td>
                            @if(!empty($h->end_at))
                                @if(strtotime($h->end_at) < strtotime(date('Y-m-d')))
                                    <span class="text-danger">{{date('d-m-Y',strtotime($h->end_at))}}</span>
                                @else
                                    {{date('d-m-Y',strtotime($h->end_at))}}
                                @endif
                            @endif
                        </td>
                        <td> {{$assigned_name}} </td>
                        <td>{{$h->priority}}</td>
                        
                        @if(Auth::user()->hasPermissionTo('mytasks.view') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    
                        <td>
                            @if(Auth::user()->hasPermissionTo('mytasks.view') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                                <a onclick='view_modal("{{$h->id}}")' class="btn btn-success btn-sm text-white">
                                    <i class="mdi mdi-pen"></i>
                                </a>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

@include('mytask.view')

@endsection