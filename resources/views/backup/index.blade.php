@extends('layouts.master')
@section('title', 'Database Backup')

@push('style')

@endpush

@push('custom-scripts')
    
    <script>
        $(function() {
            $("#database_table").DataTable({
                dom: 'Blfrtip',
                buttons: [
                    {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                    exportOptions: {
                        columns: [ 0, 1, 2,3 ] 
                    }
                    },
                    {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3 ] //Your Column value those you want
                        }
                    }
                ]
            });
            

        });
        
        function delete_modal(row_id){
            var delete_url = "{{url('backup')}}/"+row_id;
            $("#delete_form").attr('action',delete_url);
            $("#delete_modal").modal('show');
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
            <div class="col-md-9">
                <h4 class="card-title">Database Backups</h4>
            </div>
            @if(Auth::user()->hasPermissionTo('backup.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN) )
            <div class="col-md-3 text-end" >
              <a href="{{url('backup/create')}}" class="btn btn-inverse-primary btn-sm">{{__("Create Database Backup")}}</a>
            </div>
            @endif
        </div>
        
        <div class="table-responsive">
          <table id="database_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th> Name</th>
                <th>Size</th>
                <th>date</th>
                @if(Auth::user()->hasPermissionTo('backup.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                <th>Action</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($backups as $key => $file)
              
                <tr>
                    <td>{{$key+1}}</td>
                    <td>
                      <a href="{{ URL::temporarySignedRoute('backup.download', now()->addMinutes(1), ['filename' => basename($file)]) }}" >{{ basename($file)  }}
											</a>
                    </td>
                    <td>
                    {{bytesToSize($file)}}
                    </td>
                    <td data-order="{{ strftime('%Y-%m-%d %H:%M:%S', filectime($fullPath)) }}">
											{{ date('M dS, Y, g:i a',filectime($fullPath)) }}
										</td>
                    @if( Auth::user()->hasPermissionTo('backup.delete') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    <td>
                        <a href="{{url('backup/delete/')}}" onclick="return confirm(this);" class="btn btn-danger btn-sm text-white">
                            <i class=" mdi mdi-delete-forever"></i>
                        </a>
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


@endsection