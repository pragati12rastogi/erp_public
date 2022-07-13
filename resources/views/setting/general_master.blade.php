@extends('layouts.master')
@section('title', 'General Setting')

@push('style')

@endpush

@push('custom-scripts')
    <script>
        $(function() {
            
            jQuery('#general_master_form').validate({ // initialize the plugin
                rules: {

                    
                    project_name:{
                        required:true,
                    },
                    APP_URL:{
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
                <h4 class="card-title">General Setting</h4>
            </div>
            
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <form id="general_master_form" method="post" enctype="multipart/form-data" action="{{route('general.setting.save')}}" data-parsley-validate class="form-horizontal form-label-left">
                {{csrf_field()}}
                
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                            Project Name: <span class="required">*</span>
                            </label>

                            <input placeholder="Please enter Project name" type="text" id="a1" name="project_name"
                            value="{{ env('APP_NAME') }}" class="form-control currency-icon-picker ">
                            <small class="text-muted"><i class="mdi mdi-alert-octagon-outline"></i> Project name is basically your Project
                            Title.</small>
                            @error('project_name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6" style="display:none">

                        <div class="form-group">
                            <label>APP URL:<span class="required">*</span></label>
                            <input placeholder="http://" type="text" id="app_url" name="APP_URL" value="{{ env('APP_URL') }}"
                            class="form-control">
                            <small class="text-muted"><i class="mdi mdi-alert-octagon-outline"></i> Try changing domain will cause serious
                            error.</small>
                            @error('APP_URL')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-9">
                            <label>Logo:<span class="required">*</span></label>
                            <input type="file" id="first-name" name="logo" class="form-control p-2" accept="image/*">
                            <small class="text-muted"><i class="mdi mdi-alert-octagon-outline"></i> Please choose a site logo (supported
                                format: <b>PNG, JPG, JPEG, GIF</b>).</small>
                            </div>

                            <div class="col-custom col-md-3">
                            @if(!empty($setting))
                            <div class="badge-secondary card card-body">

                                <img title="Current Logo" src=" {{url('/images/general/'.$setting->logo)}}" class="w-100">

                            </div>
                            @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Favicon:</label><span class="required">*</span>
                                    <input type="file" id="first-name" name="favicon" class="form-control p-2" accept="image/*">
                                    <small class="text-muted"><i class="mdi mdi-alert-octagon-outline"></i> Please choose a site favicon (supported
                                    format: <b>PNG, JPG, JPEG</b>).</small>
                                </div>
                            </div>

                            <div class="col-custom col-md-3">
                                @if(!empty($setting))
                                <div class="badge-secondary card card-body">
                                    <center><img class="img-responsive w-100" title="Current Favicon"
                                        src=" {{url('/images/general/'.$setting->favicon)}}" ></center>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>OneSignal App Id: </label>
                            <input name="ONESIGNAL_APP_ID" type="text" value="{{ env('ONESIGNAL_APP_ID') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>OneSignal Rest API Key: </label>
                            <input name="ONESIGNAL_REST_API_KEY" type="text" value="{{ env('ONESIGNAL_REST_API_KEY') }}" class="form-control">
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facebook App Id: </label>
                            <input name="FACEBOOK_APP_ID" type="text" value="{{ env('FACEBOOK_APP_ID') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facebook App Secret: </label>
                            <input name="FACEBOOK_APP_SECRET" type="text" value="{{ env('FACEBOOK_APP_SECRET') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facebook Graph Version: </label>
                            <input name="FACEBOOK_GRAPH_VERSION" type="text" value="{{ env('FACEBOOK_GRAPH_VERSION') }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facebook Webhook URL: </label>
                            <input name="FACEBOOK_WEBHOOK_URL" type="text" value="{{ route('webhook.index') }}" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facebook Verify Token: </label>
                            <input name="FACEBOOK_VERIFY_TOKEN" type="text" value="abc123" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <hr>
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