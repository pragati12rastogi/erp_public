@extends('layouts.master')
@section('title', 'Purchase Order Summary')

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
{!! Html::script('/js/admin_po.js') !!}
  <script>
      var table;
      const table_index = 3;

      $(function() {

        if(table)
          table.destroy();

          table = $("#purchase_table").DataTable({
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

          jQuery('#adminpurchase_form').validate({ // initialize the plugin
            rules: {
                vendor_id:{
                    required:true,
                },
                'gst_types[]':{
                    required:true,
                },
                'prod':{
                    required:true,
                }

            },
            messages:{
                prod: "Add products for distribution"
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
      $('.date-range-filter').on('change', function () {
          table.draw();
      });
      
      
  </script>
  {!! Html::script('/js/datefilter.js') !!}
@endpush

@section('content')
<div class="row">
  
  <div class="col-lg-12">
  @include('flash-msg')
  </div>
  @if(Auth::user()->hasPermissionTo('purchase-order.create') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
    @include('purchase_order.create')
  @endif
  <div class="col-lg-12 grid-margin stretch-card">
  
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-9">
                <h4 class="card-title">Purchase Order Summary</h4>
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
          
          <table id="purchase_table" class="table ">
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
              @foreach($purchase as $did =>$dv)
                <tr>
                  
                  <td>PO-INV{{str_pad($dv->id,5,"0",STR_PAD_LEFT)}}</td>
                  <td>{{$dv->vendor->name}}</td>
                  
                  <td> Rs. {{$dv->total_cost}}</td>
                  <td>{{date('Y-m-d',strtotime($dv->created_at))}}</td>
                  <td>{{$dv->created_by_user->name}}</td>
                  <td>
                    @if(Auth::user()->hasPermissionTo('purchase-order.show') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                    <a href="{{url('purchase-order/'.$dv->id)}}" class="btn btn-sm btn-success ">
                      Invoice
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



@endsection