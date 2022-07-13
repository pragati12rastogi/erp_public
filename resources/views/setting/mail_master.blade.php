@extends('layouts.master')
@section('title', 'Email Setting')

@push('style')

@endpush

@push('custom-scripts')
<script>
    $(function() {
    
        const togglePassword = document.querySelector('#password-toggle');
        const password = document.querySelector('#password-field');
        togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye / eye slash icon
        this.classList.toggle('mdi-eye-off');
        });
        $('#mail_form').validate({ // initialize the plugin
            rules: {

                MAIL_FROM_NAME: {
                    required: true
                },
                MAIL_MAILER:{
                    required:true,
                },
                MAIL_FROM_ADDRESS:{
                    required:true
                },
                MAIL_HOST:{
                    required:true
                },
                MAIL_PORT:{
                    required:true
                },
                MAIL_USERNAME:{
                    required:true
                },
                MAIL_PASSWORD:{
                    required:true
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
                <h4 class="card-title">Email Setting</h4>
            </div>
            
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('email.setting.save') }}" method="POST" id="mail_form">
                    
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MAIL_FROM_NAME">Sender Name:</label>
                                <input type="text" placeholder="Enter sender name" name="MAIL_FROM_NAME"  value="{{ $env_files['MAIL_FROM_NAME'] }}"  class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group"><label for="MAIL_MAILER">Mail Driver: (ex. smtp,sendmail,mail)</label>
                            <input type="text" name="MAIL_MAILER" value="{{ $env_files['MAIL_MAILER'] }}" class="form-control"></div>
                        </div>

                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="MAIL_MAILER">Mail Address: (ex. user@info.com)</label>
                            <input type="text" name="MAIL_FROM_ADDRESS" value="{{ $env_files['MAIL_FROM_ADDRESS'] }}" class="form-control">
                            </div>
                        </div>

                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="MAIL_HOST">Mail Host: (ex. smtp.gmail.com)</label>
                                <input placeholder="Enter mail host" type="text" name="MAIL_HOST" value="{{ $env_files['MAIL_HOST'] }}" class="form-control">
                            </div>
                        </div>

                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="MAIL_PORT">Mail PORT: (ex. 467,587,2525) </label>
                                <input type="text" placeholder="Enter mail port" name="MAIL_PORT" value="{{ $env_files['MAIL_PORT'] }}" class="form-control">
                            </div>
                        </div>

                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="MAIL_USERNAME">Mail Username: (info@gmail.com)</label>
                                <input placeholder="Enter mail Username" type="text" name="MAIL_USERNAME" value="{{ $env_files['MAIL_USERNAME'] }}" class="form-control">
                            </div>
                        </div>
            
                        <div class=" col-md-6">
                            <label for="MAIL_PASSWORD">Mail Password:</label>
                            <div class="input-group">
                                
                                <input type="password" value="{{ $env_files['MAIL_PASSWORD'] }}" name="MAIL_PASSWORD" id="password-field" type="password" placeholder="Please Enter Mail Password" class="form-control col-md-11">
                                <div class="input-group-append">
                                    <div class="input-group-text " >
                                        <span id="password-toggle" class="mdi mdi-eye toggle-password"></span> 
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MAIL_ENCRYPTION">Mail Encryption: (ex. TLS,SSL,OR Leave blank)</label>
                                <input placeholder="Enter mail encryption" type="text" value="{{ $env_files['MAIL_ENCRYPTION'] }}" name="MAIL_ENCRYPTION" class="form-control"> 
                            </div>
                            
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <button type="submit"  class="btn btn-dark mt-3">
                                <i class="fa fa-save"></i> Save Settings
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
<div class="modal fade" id="help" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
        <h4 class="modal-title" id="myModalLabel">Help ?</h4>
      </div>
      <div class="modal-body">
        <p>For Mail Detail Section: Enter the name with no spaces. Their are three Mail Drivers: <b>SMTP, Mail, sendmail, if SMTP is not working then check sendmail.</b></p>
        
        <blockquote>
          <ul>
            <li>Gmail SMTP setup settings:</li>
            <li>SMTP username: Your Gmail address.</li>
            <li>SMTP password: Your Gmail password. If Using Gmail then Use App Password. <a href="https://support.google.com/accounts/answer/185833?hl=en">Process of App Password</a>.</li>
            <li>SMTP server address: smtp.gmail.com.</li>
            <li>Gmail SMTP port (TLS): 587.</li>
            <li>SMTP port (SSL): 465.</li>
            <li>SMTP TLS/SSL required: yes.</li>
          </ul>

        </blockquote>

      </div>
      
    </div>
  </div>
</div>
@endsection


