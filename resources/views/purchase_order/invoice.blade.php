<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <title>Print Invoice: PO-INV{{str_pad($dis->id,5,"0",STR_PAD_LEFT)}}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
    
	<style>
        .padding-15{
            padding : 15px;
        } 
        .signature-box{
            border:1px solid black;
            padding:30px
        }
	</style>
    
</head>
<body>
<div class="container-fluid">
    <h3 class="text-center">Invoice: PO-INV{{str_pad($dis->id,5,"0",STR_PAD_LEFT)}}</h3>
    <div class="row justify-content-md-center">

        <div class="col-lg-12 grid-margin stretch-card">
            
            <div class="card">
            @include('flash-msg')
            
            <div class="card-body">
                <div class="border-bottom mb-3 row">
                    <div class="col-md-11">
                        <h4 class="card-title">Invoice - PO-INV{{str_pad($dis->id,5,"0",STR_PAD_LEFT)}}</h4>
                    </div>
                    
                </div>
                
                <div class="row" id="printarea">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <b>Date:</b> {{ date('d-m-Y',strtotime($dis->created_at)) }}
                                    </th>
                                    
                                    <th>
                                        <b>Invoice ID:</b> PO-INV{{str_pad($dis->id,5,"0",STR_PAD_LEFT)}}
                                        
                                    </th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                
                                <tr>
                                    <th>
                                        <b>Seller Details</b>
                                    </th>

                                    <th></th>

                                    <th>
                                        <b>Billing Details</b>
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h4>{{ $dis->vendor->firm_name }}</h4>
                                        <div>{{  $dis->vendor->address }}</div>
                                        <div><b>District :</b>{{  $dis->vendor->district_data->name }}</div>
                                        <div><b>State :</b>{{  $dis->vendor->state_data->name }}</div>
                                        <div><b>Email :</b>{{  $dis->vendor->email }}</div>
                                        <div><b>Mobile :</b>{{  $dis->vendor->phone }}</div>
                                        <div><b>GST No :</b>{{  $dis->vendor->gst_no }}</div>
                                    </td>
                                    <td>
                                        <h4>{{ $dis->created_by_user->firm_name }}</h4>
                                        <div>{{  $dis->created_by_user->address }}</div>
                                        <div><b>Area :</b>{{  $dis->created_by_user->area->name }}</div>
                                        <div><b>District :</b>{{  $dis->created_by_user->district_data->name }}</div>
                                        <div><b>State :</b>{{  $dis->created_by_user->state->name }}</div>
                                        <div><b>Email :</b>{{  $dis->created_by_user->email }}</div>
                                        <div><b>Mobile :</b>{{  $dis->created_by_user->mobile }}</div>
                                        <div><b>GST No :</b>{{  $dis->created_by_user->gst_no }}</div>
                                        
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>TAX</th>
                                    <th>Total</th>
                                    
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($dis->po_items as $in => $inv)
                                <tr>
                                    <td>
                                        <b>{{$inv->item->name}}</b>
                                        <br>
                                        @php

                                            $p =100;
                                            $gst_percent_db = $inv->gst_percent;
                                            $single_price = $inv->product_price;
                                            $tax = $single_price * ($gst_percent_db/$p);
                                            $tax = sprintf("%.2f",$tax);

                                        @endphp
                                        <small class="tax"><b>Price:</b> Rs.
                                            {{ number_format((float)$single_price, 2, '.', '')}}
                                        </small>
                                        <small class="tax"><b>Tax:</b> Rs.
                                            
                                            {{ number_format((float)$tax , 2, '.', '')}}
                                            
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
                                        @if(!empty($inv->sgst))
                                            <p>Rs. {{ sprintf("%.2f",$inv->sgst) }} <b>(SGST)</b></p>
                                        @endif
                                        @if(!empty($inv->cgst))
                                            <p>Rs. {{ sprintf("%.2f",$inv->cgst) }} <b>(CGST)</b></p>
                                        @endif
                                        @if(!empty($inv->igst))
                                            <p>Rs. {{ sprintf("%.2f",$inv->igst) }} <b>(IGST)</b></p>
                                        @endif
                                        <small class="help-block">(Tax Multiplied with Qty.)</small>
                                    </td>
                                    <td>
                                        Rs.
                                        
                                            {{ round($inv->product_total_price,2) }}
                                        <br>
                                        <small class="help-block">(Incl. of Tax )</small>
                                    </td>
                                    
                                </tr>
                                @endforeach

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <b>Grand Total:</b>
                                    </td>
                                    <td>
                                    Rs.
                                    {{ round($dis->total_cost,2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table ">
                            <tr>
                                <td width="50%">
                                    <label>Seller Signature:</label>
                                    <div class="signature-box">

                                    </div>
                                </td>
                                <td width="50%">
                                    <label>Customer Signature:</label>
                                    <div class="signature-box">

                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

