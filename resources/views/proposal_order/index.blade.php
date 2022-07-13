@extends('layouts.master')
@section('title', 'Proposal Order')

@push('style')
<style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }
    </style>
@endpush

@push('custom-scripts')
{!! Html::script('/js/proposal_order.js') !!}
  <script>
      var table;
      const table_index = 3;
      $(function() {
        if(table)
        table.destroy();

        table =$("#proposal_table").DataTable({
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
                    columns: [0, 1, 2,3  ] //Your Column value those you want
                }
            }
            
            ],

            "order":[[0,'desc']]
          });
          
      });
      $('.date-range-filter').on('change', function () {
        table.draw();
      });

        function delete_modal(row_id){
            var delete_url = "{{url('proposal-orders')}}/"+row_id;
            $("#delete_form").attr('action',delete_url);
            $("#delete_modal").modal('show');
        }
  </script>
{!! Html::script('/js/datefilter.js') !!}
@endpush

@section('content')
<div class="row">
  
  <div class="col-lg-12">
  @include('flash-msg')
  </div>
  @if(Auth::user()->hasPermissionTo('proposal-orders.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    @include('proposal_order.create')
  @endif
  <div class="col-lg-12 grid-margin stretch-card">
  
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-9">
                <h4 class="card-title">Proposal Order Summary</h4>
            </div>
            <div class="col-md-3 text-end" >
              
            </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="input-group input-daterange">
            <input autocomplete="off" type="date" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="From:">
            <div class="input-group-text">To</div>
            <input autocomplete="off" type="date" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="To:">
          </div>
        </div>
        <div class="table-responsive">
          
          <table id="proposal_table" class="table ">
            <thead>
              <tr>
                <th>Invoice No.</th>
                <th>User Name</th>
                <th>Total Amount</th>
                <th>Created At</th>
                <th>Created By</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($distribution as $did =>$dv)
                <tr>
                  
                  <td>{{$dv->invoice_no}}</td>
                  <td>{{$dv->user->name}}</td>
                  
                  <td> Rs. {{$dv->total_cost}}</td>
                  <td>{{date('Y-m-d',strtotime($dv->created_at))}}</td>
                  <td>{{$dv->created_by_user->name}}</td>
                  <td>
                    @if(Auth::user()->hasPermissionTo('proposal-orders.show') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    <a href="{{url('proposal-orders/'.$dv->id)}}" class="btn btn-success btn-sm">
                        Invoice
                    </a>
                    @endif
                    @if(Auth::user()->hasPermissionTo('proposal-orders.edit') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    <form method="post" action="{{url('proposal-orders/'.$dv->id)}}" class="pull-right d-inline" >
                            {{csrf_field()}}
                            {{method_field("PUT")}}
                        <button type="submit" class="btn btn-sm btn-info">Convert To Invoice</button>
                    </form>
                    
                    @endif
                    @if(Auth::user()->hasPermissionTo('proposal-orders.destroy') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                        <a onclick='delete_modal("{{$dv->id}}")' class="btn btn-danger btn-sm text-white">
                          Delete
                        </a>
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
            <p>Do you really want to Delete this proposal? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
            <form method="post" action="" class="pull-right" id="delete_form">
                            {{csrf_field()}}
                            {{method_field("DELETE")}}
                                
                            
            
                <button type="reset" class="btn btn-gradient-danger translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
            </form>
            </div>
        </div>
      </div>
    </div>


@endsection