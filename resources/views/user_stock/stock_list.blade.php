@extends('layouts.master')
@section('title', 'User Stock Summary')

@push('style')

@endpush

@push('custom-scripts')
    <script>
      $(function() {
          $("#stock_table").DataTable({
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
            
            ]
          });

          $("#request_product_form").validate({
            rules:{
              quantity:{
                required:true
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
          })
      });
      
      function openRequestModal(prod_id,prod_name){
        $("#modal_prod_id").val(prod_id);
        $("#modal_prod_name").val(prod_name);
        $("#request_modal").modal('show');
      }
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
                <h4 class="card-title">User Stock Summary</h4>
            </div>
            
        </div>
        
        <div class="table-responsive">
          <table id="stock_table" class="table ">
            <thead>
              <tr>
                <th>Sr.no.</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price(single unit)</th>
                <th>Action</th>
                
              </tr>
            </thead>
            <tbody>
              @foreach($user_stock as $key => $h)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$h->item->name}}</td>
                    <td>{{$h->prod_quantity}}</td>
                    <td>{{$h->price}}</td>
                    <td>
                      @if(!empty($h->item->deleted_at))
                        <span>This Item can't be requested any more!!</span>
                      @else
                        <a onclick='openRequestModal("{{$h->item_id}}","{{$h->item->name}}");'  class="btn btn-behance text-white"> Request </a> 
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

<!-- request Modal-->
<div id="request_modal" class="modal fade" role="dialog">
    <div class="modal-dialog ">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-heading">Request Product</h4>
            <button type="button" class="btn-close m-0 p-1" data-dismiss="modal"></button>
            
          </div>
          <div class="modal-body text-center">
            <div class="col-md-12">
              
              <form id="request_product_form" method="post" action="{{route('request.product')}}">
                @csrf
                
                <div class="row text-left">
                  <input type="hidden" name="item_id" id="modal_prod_id" value="0">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label" for="first-name">
                        Product Name: <span class="required">*</span>
                      </label>
                      <input type="text" disabled class="form-control" id="modal_prod_name" name="prod_name" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label" for="first-name">
                        Quantity: <span class="required">*</span>
                      </label>
                      <input type="number" name="quantity" class="form-control" required>
                    </div>
                  </div>
                </div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-behance translate-y-3" >Request</button>
          </form>
            <button type="reset" class="btn btn-inverse-dark translate-y-3" data-dismiss="modal">cancel</button>
            
          </div>
      </div>
    </div>
</div>
@endsection