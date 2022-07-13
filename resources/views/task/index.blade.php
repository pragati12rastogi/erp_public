@extends('layouts.master')
@section('title', 'Tasks Summary')

@push('style')
<!-- <link rel="stylesheet" href="{{asset('/css/image-file-uploader.css')}}"> -->
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
        
        function edit_modal(edit_id){
            var submit_edit_url = '{{url("tasks")}}/'+edit_id;
            var get_edit_url = submit_edit_url +'/edit';
            
            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:get_edit_url,
                success:function(result){

                    if(result != ''){
                        var tasks = result.task;
                        var attachments = result.attachment;
                        var checklists = result.checklist;
                        $('#task_form_upd').attr('action',submit_edit_url);
                        $('#name_upd').val(tasks.name);
                        $('#description_upd').val(tasks.description);
                        $('#start_at_upd').val(tasks.start_at);
                        $('#end_at_upd').val(tasks.end_at);
                        $('#priority_upd').val(tasks.priority).trigger('change');
                        $.each(tasks.assigned_to,function(ind,value){
                            $("#assigned_to_upd").find("option[value="+value+"]").prop("selected", "selected");
                        })
                        $("#upd_task_id").val(tasks.id);
                        $("#assigned_to_upd").trigger('change');

                        $("#append_after_list").empty();
                        var append_attachment = '';
                        $.each(attachments,function(atind,atval){
                            var split_file_type = (atval.file_type).split('/');
                            var split_path = (atval.path).split('/');
                            if(split_file_type[0] == 'image'){
                                append_attachment += '<div class="col-md-2 upd_appended_attachment">'+
                                    '<div class="card prod_upload_files">'+
                                        '<div class="card-header text-small">'+
                                            '<div class="row">'+
                                                '<div class="col-md-9">'+(atval.file_type).toUpperCase()+'</div>'+
                                                '<div class="col-md-3">'+
                                                    '<button type="button" class="btn-close" onclick="delete_attachment('+atval.id+',this)" ></button>'+
                                                '</div>'+
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
                                append_attachment += '<div class="col-md-2 upd_appended_attachment">'+
                                    '<div class="card prod_upload_files">'+
                                        '<div class="card-header text-small">'+
                                            '<div class="row">'+
                                                '<div class="col-md-9">'+(atval.file_type).toUpperCase()+'</div>'+
                                                '<div class="col-md-3">'+
                                                    '<button type="button" class="btn-close" onclick="delete_attachment('+atval.id+',this)" ></button>'+
                                                '</div>'+
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
                                    '<input type="checkbox" class="my-auto m-1" value="1" onclick="mark_checklist('+checkval.id+')" '+check_select+'>'+
                                    '<input type="text" name="upd_checklist['+checkval.id+']" value="'+checkval.checklist+'" class="form-control">'+
                                    '<div class="input-group-prepend">'+
                                        '<button type="button" class="input-group-text mdi mdi-close text-white bg-danger" onclick="upd_delete_checklist('+checkval.id+',this)">'+
                                        '</button>'+
                                    '</div>'+
                                '</div>'+
                                '<span class="text-small"> Created by '+checkval.created_by_user.name+' '+ check_completed_by+'</span>'+
                            '</div>';
                        })
                        $("#append-more-checklist_upd").append(append_checklist);
                        $("#edit_modal").modal('show');
                    }else{
                        alert('some error occured, please refresh page and try again');
                    }

                },
                error:function(error){
                    console.log(error.responseText);
                }
            })
        }

        function delete_modal(row_id){
            var delete_url = "{{url('tasks')}}/"+row_id;
            $("#delete_form").attr('action',delete_url);
            $("#delete_modal").modal('show');
        }

        function upd_delete_checklist(checklist_id, selected_ele){
            var main_div = $(selected_ele).parents(".appended-checklist-content").remove();
            $.ajax({
                type: 'GET',
                dataType:'JSON',
                url: "{{url('task/checklist/delete')}}/"+checklist_id,
                success:function(result){
                    console.log(result);
                },
                error:function(error){
                    console.log(error.responseText);
                }
            });
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
            <div class="col-md-8">
                <h4 class="card-title">Tasks Summary</h4>
            </div>
            
            @if(Auth::user()->hasPermissionTo('tasks.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-2 text-end" >  
            <button onclick='return $("#add_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Task")}}</button>
            </div>
            @endif
            
            <div class="col-md-2" >
              <a href="{{route('kanban.tasks')}}" class="btn btn-inverse-primary btn-sm">{{__("Switch To Kanban")}}</a>
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
                        
                        @if(Auth::user()->hasPermissionTo('tasks.destroy') || Auth::user()->hasPermissionTo('tasks.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    
                        <td>
                            @if(Auth::user()->hasPermissionTo('tasks.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                                <a onclick='edit_modal("{{$h->id}}")' class="btn btn-success btn-sm text-white">
                                    <i class="mdi mdi-pen"></i>
                                </a>
                            @endif

                            @if(Auth::user()->hasPermissionTo('tasks.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                                <a onclick='delete_modal("{{$h->id}}")' class="btn btn-danger btn-sm text-white">
                                    <i class="mdi mdi-delete"></i>
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

@include('task.create')
@include('task.edit')

    <div id="delete_modal" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-dismiss="modal"></button>
                <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
                <h4 class="modal-heading">Are You Sure ?</h4>
                <p>Do you really want to delete this Task? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <form method="post" id="delete_form" action="" class="pull-right">
                {{csrf_field()}}
                {{method_field("DELETE")}}
        
                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
                </form>
            </div>
            </div>
        </div>
    </div>  

@endsection