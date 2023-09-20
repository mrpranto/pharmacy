@extends('layout.master2')
@section('title', 'Login')
@push('style')
@endpush
@section('content')
    <div class="page-content d-flex align-items-center justify-content-center"
         style="background-image: url(http://127.0.0.1:8000/images/login-background.jpg);background-size: cover;">

        <div class="row w-100 mx-0 auth-page">
            <div class="col-md-6 col-xl-4 mx-auto">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12 pl-md-0">
                            <div class="auth-form-wrapper px-5 py-5">
                                <a href="#" class="noble-ui-logo text-center d-block mb-2">Chief <span>Drug</span></a>
                                <h5 class="text-muted text-center font-weight-normal mb-4">Welcome back! Log in to your
                                    account.</h5>
                                <img class="rounded mx-auto d-block"
                                     id="monkey"
                                     src=""
                                     alt="monkey-look"
                                     width="100px">

                                <form class="forms-sample" id="login-form" method="post" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group @error('email_or_phone') has-danger @enderror">
                                        <label for="exampleInputEmail1">Email or Phone </label>
                                        <input type="text"
                                               id="email"
                                               class="form-control @error('email_or_phone') form-control-danger @enderror"
                                               name="email_or_phone"
                                               placeholder="Email or phone number">
                                        @error('email_or_phone')
                                        <label id="name-error" class="error mt-2 text-danger" for="name">
                                            {{ $message }}
                                        </label>
                                        @enderror
                                    </div>
                                    <div class="form-group  @error('password') has-danger @enderror">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password"
                                               id="password"
                                               class="form-control @error('password') form-control-danger @enderror"
                                               name="password" placeholder="Password">
                                        @error('password')
                                        <label id="name-error" class="error mt-2 text-danger" for="name">
                                            {{ $message }}
                                        </label>
                                        @enderror
                                    </div>
                                    <div class="mt-3 mb-5">
                                        <button type="submit" class="btn btn-primary btn-icon-text float-right mb-md-0">
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

@push('custom-scripts')
    <script>
        $(document).ready(function () {
            $("#email").on('focus', function () {
                $("#monkey").attr("src", "{{ asset('/images/monky-look.png') }}")
            })
            $("#password").on('focus', function () {
                $("#monkey").attr("src", "{{ asset('/images/monky-not-look.png') }}")
            })
            $("#monkey").attr("src", "{{ asset('/images/monky-look.png') }}")

            @error('email_or_phone')
                document.getElementById('email').classList.add('error-shake');
                setTimeout(() => {
                    document.getElementById('email').classList.remove('error-shake');
                }, 2000);
            @enderror

            @error('password')
                document.getElementById('password').classList.add('error-shake');
                setTimeout(() => {
                    document.getElementById('password').classList.remove('error-shake');
                }, 2000);
            @enderror
        })
    </script>
@endpush
