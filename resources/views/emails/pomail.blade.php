<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        /* Base */

        body,
        body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif,
                'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            position: relative;
        }

        body {
            -webkit-text-size-adjust: none;
            background-color: #ffffff;
            color: #718096;
            height: 100%;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }

        p,
        ul,
        ol,
        blockquote {
            line-height: 1.4;
            text-align: left;
        }

        a {
            color: #3869d4;
        }

        a img {
            border: none;
        }

        /* Typography */

        h1 {
            color: #3d4852;
            font-size: 18px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }

        h2 {
            font-size: 16px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }

        h3 {
            font-size: 14px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }

        p {
            font-size: 16px;
            line-height: 1.5em;
            margin-top: 0;
            text-align: left;
        }

        p.sub {
            font-size: 12px;
        }

        img {
            max-width: 100%;
        }

        /* Layout */

        .wrapper {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
            background-color: #edf2f7;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .content {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        /* Header */

        .header {
            padding: 25px 0;
            text-align: center;
        }

        .header a {
            color: #3d4852;
            font-size: 19px;
            font-weight: bold;
            text-decoration: none;
        }

        /* Logo */

        .logo {
            height: 75px;
            width: 75px;
        }

        /* Body */

        .body {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
            background-color: #edf2f7;
            border-bottom: 1px solid #edf2f7;
            border-top: 1px solid #edf2f7;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .inner-body {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 570px;
            background-color: #ffffff;
            border-color: #e8e5ef;
            border-radius: 2px;
            border-width: 1px;
            box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015);
            margin: 0 auto;
            padding: 0;
            width: 570px;
        }

        /* Subcopy */

        .subcopy {
            border-top: 1px solid #e8e5ef;
            margin-top: 25px;
            padding-top: 25px;
        }

        .subcopy p {
            font-size: 14px;
        }

        /* Footer */

        .footer {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 570px;
            margin: 0 auto;
            padding: 0;
            text-align: center;
            width: 570px;
        }

        .footer p {
            color: #b0adc5;
            font-size: 12px;
            text-align: center;
        }

        .footer a {
            color: #b0adc5;
            text-decoration: underline;
        }

        /* Tables */

        .table table {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
            margin: 30px auto;
            width: 100%;
        }

        .table th {
            border-bottom: 1px solid #edeff2;
            margin: 0;
            padding-bottom: 8px;
        }

        .table td {
            color: #74787e;
            font-size: 15px;
            line-height: 18px;
            margin: 0;
            padding: 10px 0;
        }

        .content-cell {
            max-width: 100vw;
            padding: 32px;
        }

        /* Buttons */

        .action {
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
            margin: 30px auto;
            padding: 0;
            text-align: center;
            width: 100%;
        }

        .button {
            -webkit-text-size-adjust: none;
            border-radius: 4px;
            color: #fff !important;
            display: inline-block;
            overflow: hidden;
            text-decoration: none;
        }

        .button-blue,
        .button-primary {
            background-color: #2d3748;
            border-bottom: 8px solid #2d3748;
            border-left: 18px solid #2d3748;
            border-right: 18px solid #2d3748;
            border-top: 8px solid #2d3748;
        }

        .button-green,
        .button-success {
            background-color: #48bb78;
            border-bottom: 8px solid #48bb78;
            border-left: 18px solid #48bb78;
            border-right: 18px solid #48bb78;
            border-top: 8px solid #48bb78;
        }

        .button-red,
        .button-error {
            background-color: #e53e3e;
            border-bottom: 8px solid #e53e3e;
            border-left: 18px solid #e53e3e;
            border-right: 18px solid #e53e3e;
            border-top: 8px solid #e53e3e;
        }

        /* Panels */

        .panel {
            border-left: #2d3748 solid 4px;
            margin: 21px 0;
        }

        .panel-content {
            background-color: #edf2f7;
            color: #718096;
            padding: 16px;
        }

        .panel-content p {
            color: #718096;
        }

        .panel-item {
            padding: 0;
        }

        .panel-item p:last-of-type {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        /* Utilities */

        .break-all {
            word-break: break-all;
        }

        @media only screen and (max-width: 600px) {
        .inner-body {
        width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
        }

        @media only screen and (max-width: 500px) {
        .button {
        width: 100% !important;
        }
        }
        .app_main_head{
            box-sizing: border-box;
            font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
            color: #3d4852 !important;
            font-size: 19px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }
        .signature-box{
            border:1px solid black;
            padding:30px
        }
    </style>
    
  </head>
  <body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <td style="text-align: center;padding: 20px;">
                    <a href="{{env('APP_URL')}}" class="app_main_head">{{env('APP_NAME')}}</a>
                    </td>
                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                            <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
                                        <p><a href="#" class="button button-{{ $color ?? 'primary' }}" target="_blank" rel="noopener">{{ __('Order') }} <b>{{ __('#') }}PO-INV{{str_pad($order->id,5,"0",STR_PAD_LEFT)}}</b>
                                        </a></p>
                                        
                                        <h2 align="center">{{ __('Order #') }}PO-INV{{str_pad($order->id,5,"0",STR_PAD_LEFT)}} </h2>
                                        <hr>
                                        <table align="center" class="table table-bordered">
                                        <thead>
                                          <th>{{ __('Order Date') }}</th>
                                          
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>
                                              {{ date('d/m/Y',strtotime($order->created_at)) }}
                                            </td>
                                            
                                          </tr>
                                        </tbody>
                                        </table> 
                                        <hr>
                                        <br>
                                        <table align="center" class="table table-bordered">
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
                                                        <h4>{{ $order->vendor->firm_name }}</h4>
                                                        <div>{{  $order->vendor->address }}</div>
                                                        <div><b>District :</b>{{  $order->vendor->district_data->name }}</div>
                                                        <div><b>State :</b>{{  $order->vendor->state_data->name }}</div>
                                                        <div><b>Email :</b>{{  $order->vendor->email }}</div>
                                                        <div><b>Mobile :</b>{{  $order->vendor->phone }}</div>
                                                        <div><b>GST No :</b>{{  $order->vendor->gst_no }}</div>
                                                    </td>
                                                    <td>
                                                        <h4>{{ $order->created_by_user->firm_name }}</h4>
                                                        <div>{{  $order->created_by_user->address }}</div>
                                                        <div><b>Area :</b>{{  $order->created_by_user->area->name }}</div>
                                                        <div><b>District :</b>{{  $order->created_by_user->district_data->name }}</div>
                                                        <div><b>State :</b>{{  $order->created_by_user->state->name }}</div>
                                                        <div><b>Email :</b>{{  $order->created_by_user->email }}</div>
                                                        <div><b>Mobile :</b>{{  $order->created_by_user->mobile }}</div>
                                                        <div><b>GST No :</b>{{  $order->created_by_user->gst_no }}</div>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table> 
                                        <hr>
                                        <br>
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Qty.</th>
                                                    <th>Price</th>
                                                    <th>Tax</th>
                                                    <th>Total Price</th>
                                                </tr>
                                            </thead>
                                            @foreach($order->po_items as $inv)
                                            <tr>
                                                <td >
                                                    <b>{{$inv->item->name}}</b>
                                                    <br>
                                                    @php
                                                        $p =100;
                                                        $gst_percent_db = $inv->gst_percent;
                                                        $single_price = $inv->product_price;
                                                        $tax = $single_price + ($gst_percent_db/$p);
                                                        $tax = sprintf("%.2f",$tax);
                                                        
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
                                                    @if(!empty($inv->scgst))
                                                        <p>Rs. {{ sprintf("%.2f",$inv->scgst) }} <b>(SGST)</b></p>
                                                        <p>Rs. {{ sprintf("%.2f",$inv->scgst) }} <b>(CGST)</b></p>
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
                                                    <b>Grand Total:</b>
                                                </td>
                                                <td>
                                                Rs.
                                                {{ round($order->total_cost,2) }}
                                                </td>
                                            </tr>				
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
                                        <hr>
                                        
                                        <p>{{ __('Thanks,') }}</p>
                                        <p>{{ config('app.name') }}</p>
                                        <br><br>
                                        <code class="font-size-12">{{ __('This is system generated mail please do not replay to this mail.') }}</code>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                <td class="content-cell" align="center">
                                    © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
                                </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>