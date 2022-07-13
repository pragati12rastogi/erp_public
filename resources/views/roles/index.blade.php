@extends('layouts.master')
@section('title', 'Role Summary')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {
            $("#role_table").DataTable({
                dom: 'Blfrtip',
                buttons: [
                    {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [ 0, 1 ] 
                    }
                    },
                    {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1 ] //Your Column value those you want
                        }
                    }
                ]
            });
            jQuery('#role_form').validate({ // initialize the plugin
                rules: {

                    name: {
                        required: true,
                    },
                    'permission[]':{
                        required: true
                    }
                }
            });

            @foreach($roles as $key => $role)
            jQuery('#role_form_{{$role->id}}').validate({ // initialize the plugin
                rules: {

                    name: {
                        required: true,
                    },
                    'permission[]':{
                        required: true
                    }
                }
            });
            @endforeach
        });
        
        $(".all_select_permission").click(function(){
            var check_status = this.checked;
            var data_id = $(this).attr('id');
            $("."+data_id).prop('checked', this.checked);
        })

        $(".sub_permission").click(function(){
            var check_status = this.checked;
            var get_class = $(this).attr('class');
            var split_get_class = get_class.split(' ');
            var sub_per_name = split_get_class[1];

            var count_length = $('.'+sub_per_name).length;
            var count_checked_length = $('.'+sub_per_name+':checked').length;

            if(count_length == count_checked_length ){
                $('#'+sub_per_name).prop('checked', true);
            }else{
                $('#'+sub_per_name).prop('checked', false);
            }
        })
        $(".all_select_permission_upd").click(function(){
            var check_status = this.checked;
            var data_id = $(this).attr('id');
            $("."+data_id).prop('checked', this.checked);
        })

        $(".sub_permission_upd").click(function(){
            var check_status = this.checked;
            var get_class = $(this).attr('class');
            var split_get_class = get_class.split(' ');
            var sub_per_name = split_get_class[1];

            var count_length = $('.'+sub_per_name).length;
            var count_checked_length = $('.'+sub_per_name+':checked').length;

            if(count_length == count_checked_length ){
                $('#'+sub_per_name).prop('checked', true);
            }else{
                $('#'+sub_per_name).prop('checked', false);
            }
        }) 
    </script>
@endpush

@section('content')

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    @include('flash-msg')
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Role Summary</h4>
            </div>
            <div class="col-md-2 text-end" >
              
              <a onclick='return $("#add_role_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add Role")}}</a>
            </div>
        </div>
        
        <div class="table-responsive">
          <table id="role_table" class="table ">
            <thead>
              <tr>
                <th>Sr.No.</th>
                <th>Role</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($roles as $key => $role)
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ $role->name }}</td>
                <td>
                  @if(Auth::user()->hasPermissionTo('roles.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    <a class="btn btn-sm btn-success text-white" onclick='return $("#edit_role_modal_{{$role->id}}").modal("show");' >Edit</a>
                  @endif
                  @if(Auth::user()->hasPermissionTo('users.index') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    <a onclick='return $("#{{$role->id}}_role").modal("show");'  class="btn  btn-sm btn-danger text-white"> Delete </a>  
                  @endif
                </td>
            </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@include('roles.add')
@foreach($roles as $key => $role)
    <div id="{{$role->id}}_role" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
            <h4 class="modal-heading">Are You Sure ?</h4>
            <p>Do you really want to delete this Role? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
            <form method="post" action="{{route('roles.destroy',$role->id)}}" class="pull-right">
                            {{csrf_field()}}
                            {{method_field("DELETE")}}
                                
                            
            
                <button type="reset" class="btn btn-gradient-danger translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
            </form>
            </div>
        </div>
        </div>
    </div>
    @include('roles.edit')
@endforeach
@endsection