<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <title>Print Invoice: {{ $dis->order->invoice_no }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
    <link rel="stylesheet" href="{{asset('theme/assets/css/style.css')}}">
	<style>
        .padding-15{
            padding : 15px;
        } 
	</style>
    
    <script>
        function printIT(){
            document.getElementById("hide-div").style.display = 'none';
            window.print();
        }
    </script>
    
</head>
<body>
<div class="container">
    <h3 class="text-center">Invoice: {{$dis->order->invoice_no}}</h3>
    <div class="row justify-content-md-center">

        <div class="col-lg-12 grid-margin stretch-card">
            
            <div class="card">
                
                <div class="card-body">
                    <div class="border-bottom mb-3 row">
                        <div class="col-md-11">
                            <h4 class="card-title">Invoice - {{$dis->order->invoice_no}}</h4>
                        </div>
                        <div id="hide-div">
                            <button title="Print Order" onclick="printIT()" class="p_btn pull-right btn btn-md btn-default"><i class="fa fa-print"></i></button>
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
                                            <b>Invoice ID:</b> {{$dis->order->invoice_no}}
                                            
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
                                            <b>{{ $dis->order->created_by_user->firm_name }},</b>
                                            <br><br>
                                            {{  $dis->order->created_by_user->address }},
                                            <br>
                                            {{  $dis->order->created_by_user->district }},{{  $dis->order->created_by_user->state->name }},
                                            <br>
                                            {{ $dis->order->created_by_user->email }}
                                            <br>
                                            {{ $dis->order->created_by_user->mobile }}
                                        </td>
                                        <td>
                                            <b>{{ $dis->user_name }},</b>
                                            <br><br>
                                            {{  $dis->address }},
                                            <br>
                                            {{  $dis->phone }}
                                            
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
                                    
                                    <tr>
                                        <td>
                                            <b>{{$dis->item->name}}</b>
                                            <br>
                                            @php
                                                $tax = ($dis->product_price * $dis->item->gst_percent->percent)/100;
                                                $single_price = $dis->product_price-$tax;
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
                                            {{ $dis->distributed_quantity }}
                                        </td>
                                        <td>
                                            <p><b>Price:</b> Rs.
                                                
                                                {{ round(($single_price*$dis->distributed_quantity),2) }}</p>
                                            
                                            <small class="help-block">(Price Multiplied with Qty.)</small>
                                        </td>
                                        <td>

                                           
                                            <p>Rs. {{ sprintf("%.2f",$dis->gst) }} </p>
                                            
                                            <small class="help-block">(Tax Multiplied with Qty.)</small>
                                        </td>
                                        <td>
                                            Rs.
                                            
                                                {{ round($dis->product_total_price,2) }}
                                            <br>
                                            <small class="help-block">(Incl. of Tax )</small>
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
                                        {{ round($dis->product_total_price,2) }}
                                        </td>
                                    </tr>
                                </tbody>
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
