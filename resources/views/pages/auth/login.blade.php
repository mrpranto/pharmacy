@extends('layout.master2')
@section('title', 'Login')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-6 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-4 pr-md-0">
            <div class="auth-left-wrapper" style="background-image: url({{ asset('/images/login.jpg') }})">

            </div>
          </div>
          <div class="col-md-8 pl-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <a href="#" class="noble-ui-logo d-block mb-2">Chief <span>Drug</span></a>
              <h5 class="text-muted font-weight-normal mb-4">Welcome back! Log in to your account.</h5>
              <form class="forms-sample" method="post" action="{{ route('login') }}">
                  @csrf
                  <div class="form-group @error('email_or_phone') has-danger @enderror">
                      <label for="exampleInputEmail1">Email or Phone </label>
                      <input type="text" class="form-control @error('email_or_phone') form-control-danger @enderror" name="email_or_phone" placeholder="Email or phone number">
                      @error('email_or_phone')
                      <label id="name-error" class="error mt-2 text-danger" for="name">
                          {{ $message }}
                      </label>
                      @enderror
                  </div>
                  <div class="form-group  @error('password') has-danger @enderror">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" class="form-control @error('password') form-control-danger @enderror" name="password" placeholder="Password">
                      @error('password')
                      <label id="name-error" class="error mt-2 text-danger" for="name">
                          {{ $message }}
                      </label>
                      @enderror
                  </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                      <i class="btn-icon-prepend" data-feather="key"></i>
                    Login
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
