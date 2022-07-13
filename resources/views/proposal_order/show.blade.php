@extends('layouts.master')
@section('title', 'Invoice -'.$dis->invoice_no)

@push('style')
<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=622bcec8bc9137001940e85e&product=inline-share-buttons" async="async"></script>
@endpush

@push('custom-scripts')
{!! Html::script('/js/proposal_order.js') !!}
    <script>
        
        

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
                    <div class="row">
                        <div class="col-md-12"><b>Invoice No: </b>{{$dis->invoice_no}}</div>
                        <div class="col-md-12"><b>Created   : </b>{{ date('d-m-Y',strtotime($dis->created_at)) }}</div>
                    </div>
                </div>
                
                <div class="col-md-2" id="hide-div">
                    <div class="row">
                        
                        <div class="col-md-3 d-grid">
                            <iframe src="{{route('print.proposal.order',$dis->id)}}" style="display:none;" name="frame"></iframe>

                            <button title="Print Order" onclick="frames['frame'].print()" class="btn btn-sm btn-dark btn-block">
                            <i class="m-0 mdi mdi-printer"></i>
                            </button>
                        </div>
                        <div class="col-md-5 d-grid">
                            @php $sharethisurl = route('print.proposal.order',$dis->id); @endphp
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
            
                </div>
            </div>
        </div>
    </div>
</div>




@endsection