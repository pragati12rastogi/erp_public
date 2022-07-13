<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <title>Print Invoice: {{ $dis->invoice_no }}</title>
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
    <h3 class="text-center">Invoice: {{$dis->invoice_no}}</h3>
    <div class="row justify-content-md-center">

        <div class="col-lg-12 grid-margin stretch-card">
            
            <div class="card">
            
                <div class="card-body">
                    <div class="border-bottom mb-3 row">
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-12"><b>Invoice No: </b>{{$dis->invoice_no}}</div>
                                <div class="col-md-12"><b>Created   : </b>{{ date('d-m-Y',strtotime($dis->created_at)) }}</div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row" id="printarea">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                
                                <tbody>
                                    
                                    <tr>
                                        <th width="50%">
                                            <b>Supplier Details</b>
                                        </th>

                                        <th width="50%">
                                            <b>Client Details</b>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td >
                                        {!!$billing_add->details!!}
                                        </td>
                                        <td>
                                            <h4>{{ $dis->user->firm_name }}</h4>
                                            <div>{{  $dis->user->address }}</div>
                                            <div><b>Area :</b>{{  $dis->user->area->name }}</div>
                                            <div><b>District :</b>{{  $dis->user->district_data->name }}</div>
                                            <div><b>State :</b>{{  $dis->user->state->name }}</div>
                                            <div><b>Email :</b>{{  $dis->user->email }}</div>
                                            <div><b>Mobile :</b>{{  $dis->user->mobile }}</div>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
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
                                            <br>
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
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                        <td>
                                            <b>Sub Total:</b>
                                        </td>
                                        <td>
                                        Rs.
                                        {{ round($dis->total_cost + $dis->total_discount,2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        
                                        <td>
                                            <b>Discount:</b>
                                        </td>
                                        <td>
                                        (-) Rs.{{ round($dis->total_discount,2) }}
                                        </td>
                                    </tr>
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
                                <tr >
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
