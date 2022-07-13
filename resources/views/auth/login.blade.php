@extends('layouts.master-mini')
@section('title','Login')
@section('content')

<div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('assets/images/auth/login_1.jpg') }}); background-size: cover;">
  <div class="row w-100">
    <div class="col-lg-4 mx-auto">
      <div class="auto-form-wrapper">
      @include('flash-msg')
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="label">{{ __('E-Mail Address') }}</label>
                <div class="input-group">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <div class="input-group-append">
                        <span class="input-group-text">
                        <i class="mdi mdi-email"></i>
                        </span>
                    </div>
                </div>
                @error('email')
                  <span class="invalid-feedback  d-block" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="label">{{ __('Password') }}</label>
                <div class="input-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="*********">
                <div class="input-group-append">
                    <span class="input-group-text">
                    <i class="mdi mdi-email-lock"></i>
                    </span>
                </div>
                </div>
                @error('password')
                    <span class="invalid-feedback  d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
          <div class="form-group">
            <button class="btn btn-gradient-primary submit-btn btn-block btn-lg font-weight-medium auth-form-btn">{{ __('Login') }}</button>
          </div>
          
          <div class="form-group d-flex justify-content-between">
            <div class="form-check form-check-flat mt-0 ml-4 ">
                <input class="form-check-input bottom-50" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-range" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
            @if (Route::has('password.request'))
                <a class="auth-link text-black" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
          </div>
        </form>
      </div>
      
      <p class="footer-text text-center">copyright © {{date('Y')}} Softonic Solutions. All rights reserved.</p>
    </div>
  </div>
</div>

@endsection