@extends('layouts.master')
@section('title', 'SMS Setting')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {
            
            jQuery('#sms_master_form').validate({ // initialize the plugin
                rules: {

                    TWILIO_SID:{
                        required:true,
                    },
                    TWILIO_AUTH_TOKEN:{
                        required:true,
                    },
                    TWILIO_NUMBER:{
                        required:true,
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
                <h4 class="card-title">SMS Setting</h4>
            </div>
            
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('sms.setting.save') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label>Twilio SID: <span class="text-red">*</span></label>
                                    <input name="TWILIO_SID" type="text" value="{{ env('TWILIO_SID') }}" class="form-control">
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Twilio Auth Token: <span class="text-red">*</span></label>
                                <input name="TWILIO_AUTH_TOKEN" type="text" value="{{ env('TWILIO_AUTH_TOKEN') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Twilio Number: <span class="text-red">*</span></label>
                                <input name="TWILIO_NUMBER" type="text" value="{{ env('TWILIO_NUMBER') }}" class="form-control">
                            </div>
                        </div>
        
                        <div class="col-md-12">
                            <hr>
                            <button type="submit" class="btn btn-md btn-primary">
                                 Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection