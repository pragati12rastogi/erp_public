@extends('layouts.master')
@section('title', 'Invoice Master')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {
            
            jQuery('#invoice_master_form').validate({ // initialize the plugin
                rules: {
                    prefix:{
                        required:true,
                    },
                    suffix_number_length:{
                        digits: true,
                        required: true
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
        
        $("#user_id").change(function(){
            var user_id = $("#user_id").val();
            $.ajax({
                type:"Get",
                url:"{{route('user.invoice.setting')}}",
                data:{'user_id':user_id},
                dataType:'JSON',
                success:function(response){
                    if(response.status == 'success'){
                        var input = response.data;
                        
                        $("#prefix").val(input.prefix);
                        $("#suffix_number_length").val(input.suffix_number_length);
                    }else{
                        alert('some error occured');
                    }
                },
                error:function(error){
                    alert(error.responseText);
                }
            })
        })
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
                <h4 class="card-title">Invoice Master</h4>
            </div>
            
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <form id="invoice_master_form" method="post" enctype="multipart/form-data" action="{{route('save.invoice.master')}}" data-parsley-validate class="form-horizontal form-label-left">
                {{csrf_field()}}
                
                
                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="first-name">
                                Select User: <span class="required">*</span>
                            </label>
                            <select name="user_id" id="user_id" class="form-control select2" >
                                @foreach($users as $r)
                                <option value="{{$r->user->id}}" {{!empty($invoice_setting)? (($invoice_setting->user_id==$r->user->id) ? 'selected':'' ):''}}>{{$r->user->name}}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="first-name">
                                Invoice Number Prefix: <span class="required">*</span>
                            </label>
                            <input name="prefix" id="prefix" value="{{!empty($invoice_setting)?$invoice_setting['prefix']:''}}" type="text" maxlength="255" class="form-control text-capitalize" >
                            @error('prefix')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="first-name">
                                Suffix Invoice Number: <span class="required">*</span>
                            </label>
                            <input name="suffix_number_length" id="suffix_number_length" value="{{!empty($invoice_setting)?$invoice_setting['suffix_number_length']:''}}" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" class="form-control" >
                            @error('suffix_number_length')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    

                </div>
                

                <div class="col-xs-12">
                    <hr>
                    <button type="submit" class="btn btn-dark mt-3">Save</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection