@extends('layouts.master')
@section('title', 'State Creation')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {
            $("#state_table").DataTable({
                dom: 'Blfrtip',
                buttons: [
                    {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [ 0, 1, 2 ] 
                    }
                    },
                    {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2 ] //Your Column value those you want
                        }
                    }
                ]
            });
            jQuery('#state_form').validate({ // initialize the plugin
                rules: {

                    
                    name:{
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

            jQuery('#state_form_upd').validate({ // initialize the plugin
                rules: {

                    
                    name:{
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
        
        function edit_state_modal(edit_id){
            var submit_edit_url = '{{url("states")}}/'+edit_id;
            var get_edit_url = submit_edit_url +'/edit';
            
            $.ajax({
                type:"GET",
                dataType:"JSON",
                url:get_edit_url,
                success:function(result){

                    if(result != ''){
                        var inputs = result.state;
                        $('#state_form_upd').attr('action',submit_edit_url);
                        $('#state_name_upd').val(inputs.name);
                        $("#state_edit_modal").modal('show');
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
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">State Summary</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('states.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-2 text-end" >
              <button onclick='return $("#add_state_modal").modal("show");' class="btn btn-inverse-primary btn-sm">{{__("Add State")}}</button>
            </div>
            @endif
        </div>
        
        <div class="table-responsive">
          <table id="state_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>State Name</th>
                <th>Created At</th>
                
                @if(Auth::user()->hasPermissionTo('states.edit') || Auth::user()->hasPermissionTo('states.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($states as $key => $s)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$s->name}}</td>
                    
                    <td>{{date('d-m-Y',strtotime($s->created_at))}}</td>
                    
                    @if(Auth::user()->hasPermissionTo('states.edit') || Auth::user()->hasPermissionTo('states.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                
                    <td>
                      @if(Auth::user()->hasPermissionTo('states.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                        <a onclick='edit_state_modal("{{$s->id}}")' class="btn btn-sm btn-success text-white">
                            <i class="mdi mdi-pen"></i>
                        </a>
                      @endif
                      @if(Auth::user()->hasPermissionTo('states.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                      
                        <a onclick='return $("#{{$s->id}}_state").modal("show");' class="btn btn-sm btn-danger text-white">
                            <i class=" mdi mdi-delete-forever"></i>
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



@include('state.add')
@include('state.edit')
@foreach($states as $s)
    <div id="{{$s->id}}_state" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal"></button>
            <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
            <h4 class="modal-heading">Are You Sure ?</h4>
            <p>Do you really want to delete this State? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
            <form method="post" action="{{url('/states/'.$s->id)}}" class="pull-right">
                            {{csrf_field()}}
                            {{method_field("DELETE")}}
                                
                            
            
                <button type="reset" class="btn btn-gradient-danger translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
            </form>
            </div>
        </div>
        </div>
    </div>
    
@endforeach

@endsection