@extends('layouts.master')
@section('title', 'Billing Address Master')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {
            
            jQuery('#billing_master_form').validate({ // initialize the plugin
                rules: {

                    
                    details:{
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
                <h4 class="card-title">Billing Address Master</h4>
            </div>
            
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <form id="billing_master_form" method="post" enctype="multipart/form-data" action="{{route('save.billing.master')}}" data-parsley-validate class="form-horizontal form-label-left">
                {{csrf_field()}}
                
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="first-name">
                                Billing Address: <span class="required">*</span>
                            </label>
                            <textarea name="details" class="form-control ckeditor" >{{!empty($billing_setting)?$billing_setting['details']:''}}</textarea>
                            @error('details')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <button type="submit" class="btn btn-dark mt-3">Save</button>
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