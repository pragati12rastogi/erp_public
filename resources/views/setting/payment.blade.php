@extends('layouts.master')
@section('title', 'Payment Gateway')

@push('style')

@endpush

@push('custom-scripts')
<script>
    $(function() {
    
        document.querySelectorAll('.toggle-password').forEach(item => {
    
            item.addEventListener('click', function (e) {
                var toggle_id = $(this).attr('toggle');
                var ele = document.querySelector(toggle_id);
                // toggle the type attribute
                const type = ele.getAttribute('type') === 'password' ? 'text' : 'password';
                ele.setAttribute('type', type);
                // toggle the eye / eye slash icon
                this.classList.toggle('mdi-eye-off');
            });
        });
        
    });
    
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
                <h4 class="card-title">Payment Gateway</h4>
            </div>
            
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('rpay.setting.update') }}" method="POST">
                    @csrf
                    <div class="panel-body">

                        <div class="form-group">
                            <div class="eyeCy">
                                <label for="RAZORPAY_KEY"> RazorPay Key: <span class="required">*</span></label>
                                <div class="input-group">
                                    <input type="password" value="{{env('RAZORPAY_KEY')}}" name="RAZORPAY_KEY" id="RAZORPAY_KEY" type="password"
                                        class="form-control col-md-11">
                                    <div class="input-group-append">
                                        <div class="input-group-text " >
                                            <span toggle="#RAZORPAY_KEY" class="mdi mdi-eye field-icon toggle-password"></span>
                                        </div>
                                    </div>
                                </div>

                                <small class="text-muted"><i class="fa fa-question-circle"></i> Enter
                                    Razorpay API key</small>
                                @error('RAZORPAY_KEY')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="eyeCy">
                                <label for="RAZORPAY_SECRET"> RazorPay Secret Key: <span
                                        class="required">*</span></label>
                                <div class="input-group">
                                    <input type="password" value="{{env('RAZORPAY_SECRET')}}"
                                    name="RAZORPAY_SECRET" id="RAZORPAY_SECRET" type="password"
                                    class="form-control col-md-11">
                                    <div class="input-group-append">
                                        <div class="input-group-text " >
                                            <span toggle="#RAZORPAY_SECRET" class="mdi mdi-eye field-icon toggle-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted"><i class="fa fa-question-circle"></i> Enter
                                    Razorpay secret key</small>
                                @error('RAZORPAY_SECRET')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <p></p>

                        <input {{ env('RAZORPAY_ACTIVE') ==1 ? "checked" : "" }} name="RAZORPAY_ACTIVE" id="razpay"
                            type="checkbox"
                            class="tgl tgl-skewed">
                        
                        <small class="txt-desc">(Enable to activate Razorpay Payment gateway )</small>
                        <br><br>

                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="btn btn-dark btn-sm"><i class="fa fa-save"></i> Save
                            Setting</button>
                    </div>

                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection


