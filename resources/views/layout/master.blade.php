<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/assets/images/pharmacy-favicon.png') }}">

    <!-- plugin css -->
    <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet"/>
    <!-- end plugin css -->
    <link href="{{ asset('assets/daterange/daterangepicker.css') }}" rel="stylesheet"/>

    @stack('plugin-styles')

    <!-- common css -->
    @if(session()->get('color-mode') == 'white')
        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    @elseif(session()->get('color-mode') == 'dark')
        <link href="{{ asset('css/app-dark.css') }}" rel="stylesheet"/>
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    @endif

    <!-- end common css -->

    @stack('style')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    data-base-url="{{url('/')}}"
    class="{{ request()->is('purchases/create') || request()->is('purchases/*/edit') || (request()->is('sales/create') && pos_setting() !== 'list_design') ? 'sidebar-folded' : '' }}">

<script src="{{ asset('assets/js/spinner.js') }}"></script>

<div class="main-wrapper">
    @include('layout.sidebar')
    <div class="page-wrapper">
        @include('layout.header')
        <div class="page-content" id="app">
            @yield('content')
        </div>
        @include('layout.footer')
    </div>


    <div class="modal fade offline-warning-show" tabindex="-1" role="dialog" data-backdrop="static"
         data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mt-2">
                            <div class="alert alert-danger text-center">
                                You are offline, Your app will be not running until you connection is restore.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- base js -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
<!-- end base js -->

<!-- plugin js -->
@stack('plugin-scripts')
<!-- end plugin js -->
<script src="{{ asset('assets/daterange/moment.min.js') }}"></script>
<script src="{{ asset('assets/daterange/daterangepicker.min.js') }}"></script>
<!-- common js -->
<script src="{{ asset('assets/js/template.js') }}"></script>
<script src="{{ asset('/js/clock.js') }}"></script>
<!-- end common js -->

@stack('custom-scripts')
<script>
    $(function () {
        'use strict';
        const Toast = Swal.mixin({
            toast: true,
            showConfirmButton: false,
            timer: 3000
        });

        @if(session()->get('success'))
            @if(is_array(cache('notification')))
                @php
                    $position = cache('notification')['notification_show_position'];
                    if($position == 'topLeft'){
                        $notification_position = 'top-start';
                    }elseif ($position == 'topRight'){
                        $notification_position = 'top-end';
                    }elseif ($position == 'bottomRight'){
                        $notification_position = 'bottom-end';
                    }elseif ($position == 'bottomLeft'){
                        $notification_position = 'bottom-start';
                    }elseif ($position == 'bottom'){
                        $notification_position = 'bottom';
                    }else{
                        $notification_position = 'top';
                    }
                @endphp
                Toast.fire({
                    position: "{{ $notification_position }}",
                    icon: 'success',
                    title: '{{ session()->get('success') }}'
                })
                @if(cache('notification')['notification_sound'] == 'on')
                    let audio = new Audio('{{ asset('/assets/sounds/success.mp3') }}');
                    audio.play();
                @endif
            @endif
        @endif
    });

    window.addEventListener('offline', function (event) {
        $('.offline-warning-show').modal('show')
    });
    window.addEventListener('online', function (event) {
        $('.offline-warning-show').modal('hide')
    });

    window._locale = '{{ session()->get('lang') ?? app()->getLocale() }}';
    @if(request()->is('setting'))
        window._translations = `{!! cache('translations') !!}`;
    @else
        window._translations = {!! cache('translations') !!};
    @php
        $general_setting = json_encode(cache('general_setting'));
        $notification = json_encode(cache('notification'));
        $systemSetting = json_encode(cache('system'));
    @endphp
        window._general_setting = {!! $general_setting !!};
        window._notification_setting = {!! $notification !!};
        window._system_setting = {!! $systemSetting !!};
    @endif

</script>
</body>
</html>
