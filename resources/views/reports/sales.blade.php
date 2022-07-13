@extends('layouts.master')
@section('title', 'Sales Report')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        var invoiceTable;
        var itemTable;
        var paymentTable;
        var purchaseOrderTable;
        
        $(function() {
            invoiceTableFunc();
            itemTableFunc();
            paymentTableFunc();
            purchaseOrderTableFunc();

            $('.date-range-filter,.sale_agent_class').change(function() {
                invoiceTable.draw();
                itemTable.draw();
            });
        });

        function invoiceTableFunc(){

            if(invoiceTable){
                invoiceTable.destroy();
            }

            invoiceTable = $("#invoice_table").DataTable({
                "processing": true,
                "serverSide": true,
                
                "ajax": {
                    "url":"{{url('reports/sales-invoices')}}",
                    "datatype": "json",
                    "data": function (data,json) {
                        var sale_agent_inv = $('#sale_agent_inv').val();
                        var startDate = $('#min-date').val();
                        var endDate = $('#max-date').val();
                        data.sale_agent = sale_agent_inv;
                        data.startDate = startDate;
                        data.endDate = endDate;
                    }
                },
                "responsive": true,
                "aaSorting": [],
                "columns": [
                    { "data": "invoice_no" },
                    { "data": "name"}, 
                    { "data": "created_date"}, 
                    {   "targets": [ -1 ],
                        "data": "amount","render": function(data,type,full,meta){
                            return data.toFixed(2);
                    }}, 
                    { "data": "total_tax"}, 
                    { "data": "total_discount"}, 
                    { "data": "total_cost"} ,
                ],
                dom: 'Blfrtip',
                buttons: [
                    {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6 ] 
                        }
                    },
                    {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6 ] //Your Column value those you want
                        }
                    }
                ],
                "columnDefs": [
                    { "orderable": false, "targets": 0}
                ]
            });
            
        }
        
        function itemTableFunc(){

            if(itemTable){
                itemTable.destroy();
            }

            itemTable = $("#item_table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url":"{{url('reports/sales-item')}}",
                    "datatype": "json",
                    "data": function (data,json) {
                        var sale_agent_inv = $('#sale_agent_item').val();
                        var startDate = $('#min-date-item').val();
                        var endDate = $('#max-date-item').val();
                        data.sale_agent = sale_agent_inv;
                        data.startDate = startDate;
                        data.endDate = endDate;
                    }
                },
                "responsive": true,
                "aaSorting": [],
                "columns": [
                    { "data": "name" },
                    { "data": "qty_sold"},
                    { "data": "total_amt"},
                     
                    {   "targets": [ -1 ],
                        "data": "avg_amt","render": function(data,type,full,meta){
                            return data.toFixed(2);
                    }},
                ],
                dom: 'Blfrtip',
                buttons: [
                    {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3] 
                        }
                    },
                    {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3] //Your Column value those you want
                        }
                    }
                ],
                "columnDefs": [
                    
                ]
            });
            
        }

        function paymentTableFunc(){

            if(paymentTable){
                paymentTable.destroy();
            }

            paymentTable = $("#payment_table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url":"{{url('reports/sales-payment')}}",
                    "datatype": "json",
                    "data": function (data,json) {
                        
                        var startDate = $('#min-date-item').val();
                        var endDate = $('#max-date-item').val();
                        data.startDate = startDate;
                        data.endDate = endDate;
                    }
                },
                "responsive": true,
                "aaSorting": [],
                "columns": [
                    { "data": "id" },
                    { "data": "created_date"},
                    { "data": "invoice_no"},
                    { "data": "name"},
                    { "data": "transaction_type"},
                    { "data": "transaction_id"},
                    { "data": "amount"},
                ],
                dom: 'Blfrtip',
                buttons: [
                    {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7] 
                        }
                    },
                    {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7] //Your Column value those you want
                        }
                    }
                ],
                "columnDefs": [
                    
                ]
            });

        }

        function purchaseOrderTableFunc(){

            if(purchaseOrderTable){
                purchaseOrderTable.destroy();
            }

            purchaseOrderTable = $("#credit_note_table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url":"{{url('reports/sales-purchase-order')}}",
                    "datatype": "json",
                    "data": function (data,json) {
                        
                        var startDate = $('#min-date-item').val();
                        var endDate = $('#max-date-item').val();
                        data.startDate = startDate;
                        data.endDate = endDate;
                    }
                },
                "responsive": true,
                "aaSorting": [],
                "columns": [
                    { "data": "invoice_no" },
                    { "data": "created_date"},
                    { "data": "name"}, 
                    { "data": "total_cost"} ,
                    {   "targets": [ -1 ],
                        "data": "amount","render": function(data,type,full,meta){
                            return data.toFixed(2);
                    }}, 
                    { "data": "total_tax"}, 
                    
                ],
                dom: 'Blfrtip',
                buttons: [
                    {
                    extend:'excelHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5] 
                        }
                    },
                    {
                    extend:'pdfHtml5',
                    className: 'btn-sm mb-4',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5] //Your Column value those you want
                        }
                    }
                ],
                "columnDefs": [
                    
                ]
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
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
    
      <div class="card-body">
        <div class="border-bottom mb-3 row">
            <div class="col-md-10">
                <h4 class="card-title">Sales Report</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content" id="myTab" role="tablist"><!-- flex-column -->
                    <li class="nav-item">
                        <a class="nav-link" href="#invoice" id="invoice-tab" data-toggle="tab" href="#invoice" role="tab" aria-controls="invoice" aria-selected="true"><i class="dropdown-toggle m-1"></i> Invoices Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#item" id="item-tab" data-toggle="tab" href="#item" role="tab" aria-controls="item" aria-selected="true"><i class="dropdown-toggle m-1"></i> Items Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#payment" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="true"><i class="dropdown-toggle m-1"></i> Payment Report</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#credit_note" id="credit_note-tab" data-toggle="tab" href="#credit_note" role="tab" aria-controls="credit_note" aria-selected="true"><i class="dropdown-toggle m-1"></i> Purchase Orders Report</a>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane  " id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="">Generated Report</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label class="form-label">Sale Agents</label>
                                <select class="form-control select2 sale_agent_class" id="sale_agent_inv">
                                    <option value="">All</option>
                                    @foreach($sale_agents as $sale_id => $sale_data)
                                        <option value="{{$sale_data->id}}">{{$sale_data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="form-label">Period</label>
                                <div class="input-group input-daterange">
                                    <input autocomplete="off" type="date" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="From:">
                                    <div class="input-group-text">To</div>
                                    <input autocomplete="off" type="date" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="To:">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="invoice_table" class="table " width="100%">
                                <thead>
                                    <tr>
                                        <th>#Invoice</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Tax</th>
                                        <th>Discount</th>
                                        <th>Grand Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane  " id="item" role="tabpanel" aria-labelledby="item-tab">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="">Generated Report</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label class="form-label">Sale Agents</label>
                                <select class="form-control select2 sale_agent_class" id="sale_agent_item">
                                    <option value="">All</option>
                                    @foreach($sale_agents as $sale_id => $sale_data)
                                        <option value="{{$sale_data->id}}">{{$sale_data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="form-label">Period</label>
                                <div class="input-group input-daterange">
                                    <input autocomplete="off" type="date" id="min-date-item" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="From:">
                                    <div class="input-group-text">To</div>
                                    <input autocomplete="off" type="date" id="max-date-item" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="To:">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="item_table" class="table " width="100%">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Qunatity Sold</th>
                                        <th>Total Amount</th>
                                        <th>Average Price</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane  " id="payment" role="tabpanel" aria-labelledby="payment-tab">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="">Generated Report</h4>
                        <hr>
                        
                        <div class="table-responsive">
                            <table id="payment_table" class="table " width="100%">
                                <thead>
                                    <tr>
                                        <th>#Payment</th>
                                        <th>Date</th>
                                        <th>#Invoice</th>
                                        <th>Customer</th>
                                        <th>Payment Mode</th>
                                        <th>Transaction Id</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane  " id="credit_note" role="tabpanel" aria-labelledby="credit_note-tab">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="">Generated Report</h4>
                        <hr>
                        
                        <div class="table-responsive">
                            <table id="credit_note_table" class="table " width="100%">
                                <thead>
                                    <tr>
                                        <th>#Purchase Invoice</th>
                                        <th>Date</th>
                                        <th>Vendor</th>
                                        <th>Amount</th>
                                        <th>Amount without tax</th>
                                        <th>total tax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection