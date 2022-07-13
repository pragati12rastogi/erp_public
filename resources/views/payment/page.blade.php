<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <title>Pay for Invoice: {{ $order->invoice_no }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
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
    
</head>
<body>
<div class="container my-5">
    <div class="col-lg-12">
    @include('flash-msg')
    </div>
    <div class="col-lg-12 grid-margin">
        <div class="card">
            <div class="card-header text-center">

                @if(!empty($general_settings) && !empty($general_settings['logo']) && file_exists(public_path().'/images/general/'.$general_settings['logo']) )
                    <img src="{{ url('/images/general/'.$general_settings['logo']) }}" alt="logo" /> 
                @else
                    <img src="{{ url('assets/images/logo.svg') }}" alt="logo" />
                @endif
            </div>
            <div class="card-body">
                <h4>Payment For Invoice: <b class="text-success">{{ $order->invoice_no }}</b></h4>
                <p><b>Total Amount: </b>{{$new_amt}}</p>
            </div>
            <div class="card-footer">
                <form action="{{ $url }}" method="POST" >
                @csrf
                    <input type="hidden" name="actualtotal" value="{{$new_amt}}">
                    <script src="https://checkout.razorpay.com/v1/checkout.js"
                        data-key="{{ env('RAZORPAY_KEY') }}"
                        data-amount="{{(round($new_amt,2))*100}}"
                        data-buttontext="Pay {{round($new_amt,2)}} INR"
                        data-name="{{ env('APP_NAME') }}"
                        data-description="Payment For Order {{$order->invoice_no}}"
                        data-image="{{ url('/images/general/'.$general_settings['logo']) }}"
                        data-prefill.name=""
                        data-prefill.email=""
                        data-theme.color="#ff7529">
                    </script>
                </form>
            </div>
        </div>
    </div>
    
</div>
          
</body>
</html>
