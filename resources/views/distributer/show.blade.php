@extends('layouts.master')
@section('title', 'Invoice -'.$dis->invoice_no)

@push('style')
<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=622bcec8bc9137001940e85e&product=inline-share-buttons" async="async"></script>
@endpush

@push('custom-scripts')
{!! Html::script('/js/admin_distributer.js') !!}
    <script>
        
        

    </script>
@endpush

@section('content')

<div class="row">
    
        <div class="col-md-2 mb-2">
            <div class="card bg-gradient-success">
                <div class="card-header">
                Selling Amount
                </div>
                <div class="card-body p-3">Rs. {{empty($sell['sum_sell'])? 0:$sell['sum_sell']}}</div>
            </div>
        </div>

        <div class="col-md-2 mb-2">
            <div class="card bg-gradient-success">
                <div class="card-header">@if(is_admin(Auth::user()->role_id)) Receive @else Paid @endif Amount</div>
                <div class="card-body p-3">Rs. {{empty($recieve['sum_recieve'])? 0:$recieve['sum_recieve']}}</div>
            </div>
        </div>
        
        <div class="col-md-2 mb-2">
            <div class="card bg-gradient-success">
                <div class="card-header">Balance Amount</div>
                @php
                $sale = (float) $sell['sum_sell'];
                $recieve = (float) $recieve['sum_recieve'];
                @endphp
                <div class="card-body p-3">Rs. {{$sale-$recieve}}</div>
            </div>
        </div>
    
    <div class="col-lg-12 grid-margin stretch-card">
        
        <div class="card">
            @include('flash-msg')
        
        <div class="card-body">
            <div class="border-bottom mb-3 row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12"><b>Invoice No: </b>{{$dis->invoice_no}}</div>
                        <div class="col-md-12"><b>Created   : </b>{{ date('d-m-Y',strtotime($dis->created_at)) }}</div>
                    </div>
                </div>
                
                <div class="col-md-4" id="hide-div">
                    <div class="row">
                        <div class="col-md-4 d-grid">
                            @if(empty($dis->is_cancelled))
                                @if(Auth::user()->hasPermissionTo('stock-distributions.payment') || Auth::user()->hasRole(App\Custom\Constants::ROLE_ADMIN))
                                    
                                    <a onclick='return $("#pay").modal("show");'  class="btn btn-sm btn-warning text-white"><i class="m-0 mdi mdi-vote"></i> Pay </a>  
                                @endif
                            @endif
                        </div>
                        <div class="col-md-3 d-grid">
                            <iframe src="{{route('print.invoice',$dis->id)}}" style="display:none;" name="frame"></iframe>

                            <button title="Print Order" onclick="frames['frame'].print()" class="btn btn-sm btn-dark btn-block">
                            <i class="m-0 mdi mdi-printer"></i>
                            </button>
                        </div>
                        <div class="col-md-5 d-grid">
                            @php $sharethisurl = route('print.invoice',$dis->id); @endphp
                            @include('sharethis')
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="row" id="printarea">
            <div class="col-md-12">
                    <table class="table table-borderless">
                        
                        <tr>
                            <td width="50%">
                                <div class="card border">
                                    <div class="card-header"><h4>Supplier Details</h4></div>
                                    <div class="card-body">{!!$billing_add->details!!}</div>
                                </div>
                            </td>

                            <td width="50%" >
                                <div class="card border">
                                    <div class="card-header"><h4>Client Details</h4></div>
                                    <div class="card-body ">
                                        <h4>{{ $dis->user->firm_name }}</h4>
                                        <div>{{  $dis->user->address }}</div>
                                        <div><b>Area :</b>{{  $dis->user->area->name }}</div>
                                        <div><b>District :</b>{{  $dis->user->district_data->name }}</div>
                                        <div><b>State :</b>{{  $dis->user->state->name }}</div>
                                        <div><b>Email :</b>{{  $dis->user->email }}</div>
                                        <div><b>Mobile :</b>{{  $dis->user->mobile }}</div>
                                    </div>
                                </div>
                                
                            </td>
                        </tr>
                        
                    </table>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty.</th>
                                    <th>Price</th>
                                    <th>Tax</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                @foreach($dis->invoices as $in => $inv)
                                <tr>
                                    <td>
                                        <b>{{$inv->item->name}}</b>
                                        <br>
                                        @php
                                            $p =100;
                                            $gst_percent_db = $inv->gst_percent;
                                            $p_and_gst = $p + $gst_percent_db;
                                            $tax = $inv->product_price/$p_and_gst*$gst_percent_db;
                                            $tax = sprintf("%.2f",$tax);
                                            $single_price = $inv->product_price-$tax;
                                        
                                        @endphp
                                        <small class="tax"><b>Price:</b> Rs.
                                            {{ number_format((float)$single_price, 2, '.', '')}}
                                        </small>
                                        &nbsp;
                                        <small class="tax"><b>GST:</b> Rs.
                                            
                                            {{ number_format((float)$tax , 2, '.', '')}} ({{$inv->gst_percent}} %)
                                            
                                        </small>
                                        <br>
                                        <small class="help-block">(Displayed for single Qty.)</small>
                                    </td>
                                    <td valign="middle">
                                        {{ $inv->distributed_quantity }}
                                    </td>
                                    <td>
                                        <p><b>Price:</b> Rs.
                                            
                                            {{ round(($single_price*$inv->distributed_quantity),2) }}</p>
                                        
                                        <small class="help-block">(Price Multiplied with Qty.)</small>
                                    </td>
                                    <td>
                                        @if(!empty($inv->igst))
                                            <p>Rs. {{ sprintf("%.2f",$inv->igst) }} <b>(IGST)</b></p>
                                        @endif
                                        @if(!empty($inv->sgst))
                                            <p>Rs. {{ sprintf("%.2f",$inv->sgst) }} <b>(SGST)</b></p>
                                            
                                        @endif
                                        @if(!empty($inv->cgst))
                                            <p>Rs. {{ sprintf("%.2f",$inv->cgst) }} <b>(CGST)</b></p>
                                        @endif
                                        <small class="help-block">(Tax Multiplied with Qty.)</small>
                                    </td>
                                    <td>
                                        <p> Rs.
                                            
                                        {{ round(($inv->product_total_price),2) }}</p>
                                        
                                        <small class="help-block">(Inc of Tax and Quantity.)</small>
                                    </td>
                                    
                                    
                                </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                        
                    </div>
                    <table class="table table-borderless">
                            <tr>
                                <td>
                                    <h4>Term &amp; Condition</h4>
                                    <ul>
                                        <li>
                                            No Return Accepted.
                                        </li>
                                        <li>
                                            Payable in 7 days from receipt.
                                        </li>
                                        <li>
                                            Products once sold will not be taken back or exchange.
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <table class="table">
                                        <tbody class="border">
                                            <tr class="table-secondary">
                                                <td class="border">
                                                    <b>Sub Total:</b>
                                                </td>
                                                <td class="border">
                                                Rs.{{ round($dis->total_cost+$dis->total_discount,2) }}
                                                </td>
                                            </tr>
                                            <tr class="table-secondary">
                                                <td class="border-right">
                                                    <b>Discount:</b>
                                                </td>
                                                <td>
                                                (-) Rs.{{ round($dis->total_discount,2) }}
                                                </td>
                                            </tr>
                                            <tr class="table-active">
                                                
                                                <td class="border-right">
                                                    <b>Grand Total:</b>
                                                </td>
                                                <td>
                                                Rs.
                                                {{ round($dis->total_cost,2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        
                                    </table>
                                </td>
                            </tr>

                        </table>
            </div>
            @if(count($dis->payment)>0)
            <div class="col-md-12">
                <hr>
                <h6>Payment Activity History</h6>
                <small>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Transaction Type</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($dis->payment as $pid => $p)
                        <tr>
                            <td>
                                <small>
                                    <span><b>{{ucwords($p->transaction_type)}}</b></span>
                                    <div class="row">
                                        @if(!empty($p->transaction_id))
                                        <div class="col-md-4">
                                            <b>Transaction ID:</b> <span>{{$p->transaction_id}}</span>
                                        </div>
                                        @endif
                                        @if(!empty($p->cheque_no))
                                        <div class="col-md-4">
                                            <b>Cheque No:</b> <span>{{$p->cheque_no}}</span>
                                        </div>
                                        @endif
                                        @if(!empty($p->bank_name))
                                        <div class="col-md-4">
                                            <b>Bank Name:</b> <span>{{$p->bank_name}}</span>
                                        </div>
                                        @endif
                                        @if(!empty($p->ifsc))
                                        <div class="col-md-4">
                                            <b>IFSC:</b> <span>{{$p->ifsc}}</span>
                                        </div>
                                        @endif
                                        @if(!empty($p->account_name))
                                        <div class="col-md-4">
                                            <b>Account Owner Name:</b> <span>{{$p->account_name}}</span>
                                        </div>
                                        @endif
                                    </div>
                                </small>
                            </td>
                            <td>
                                Rs. {{$p->amount}}
                            </td>
                            <td>
                                {{date('d-m-Y',strtotime($p->created_at))}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </small>
            </div>
            @endif
            </div>
        </div>
        </div>
    </div>
</div>



<div id="pay" class="delete-modal modal fade" role="dialog">
    <div class="modal-dialog ">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-heading">Payment</h4>
            <button type="button" class="btn-close m-0 p-1" data-dismiss="modal"></button>
            
        </div>
        <div class="modal-body text-center">
            <div class="col-md-12">
            @php
                
                $paid_amt = 0;
                foreach($dis->payment as $pid => $p){
                    $paid_amt += $p->amount;
                }

            @endphp
            <form id="payment_form" method="post" action="{{route('distribution.payment')}}">
                @csrf
                <div class="row">
                <div class="col-md-3 text-left text-small">
                    <label><b> Total Order Amount :</b></label>
                    <span> Rs. {{$dis->total_cost}}</span>
                </div>
                <div class="col-md-3 text-left text-small">
                    <label><b> Total Amount Paid :</b></label>
                    <span> Rs. {{$paid_amt}}</span>
                </div>
                
                <div class="col-md-3 text-left text-small">
                    <label>
                    <b> Pending Payment:</b>
                    </label>
                    <span> Rs. {{($dis->total_cost-$paid_amt<0)? abs($dis->total_cost-$paid_amt)."(Extra amt paid)":$dis->total_cost-$paid_amt}}</span>
                </div>
                </div>
                <hr>
                <div class="row text-left">
                <input type="hidden" name="admin_order_id" value="{{Crypt::encrypt($dis->id)}}">
                <div class="col-md-6">
                    <div class="form-group">
                    <label class="control-label" for="first-name">
                        Amount: <span class="required">*</span>
                    </label>
                    <input type="number" max="{{$dis->total_cost-$paid_amt}}" value="{{$dis->total_cost-$paid_amt}}" name="amount" id="amount_{{$dis->id}}" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                    <label class="control-label" for="first-name">
                        Transaction Type: <span class="required">*</span>
                    </label>
                    <select name="transaction_type" data-type="{{$dis->id}}"  class="transaction_type form-control select2">
                        <option value="">Select transaction method</option>
                        <option value="cash">Cash</option>
                        <option value="online">Online</option>
                        <option value="cheque">Cheque</option>
                    </select>
                    </div>
                </div>
                <div class="col-md-12" id="online_div_{{$dis->id}}" style="display:none">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="first-name">
                                    Transaction ID: <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" name="transaction_id">
                            </div>
                        </div>
                        <div class="col-md-6 m-auto">
                            <button type="button" class="btn btn-xs btn-gradient-danger" onclick="copyToClipboard('<?php echo url('stock-distribution/gateways/payment/'.Auth::id().'/'.Crypt::encrypt($dis->id)); ?>',this,{{$dis->id}})">Copy payment Url</button>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-12" id="cheque_div_{{$dis->id}}" style="display:none">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="control-label" for="first-name">
                            Cheque Number: <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="cheque_no">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="control-label" for="first-name">
                            Bank Name: <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="bank_name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="control-label" for="first-name">
                            IFSC: <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="ifsc">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="control-label" for="first-name">
                            Account Owner Name: <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="account_name">
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success translate-y-3" >Submit</button>
        </form>
            <button type="reset" class="btn btn-inverse-dark translate-y-3" data-dismiss="modal">Cancel</button>
            
        </div>
    </div>
    </div>
</div>
@endsection