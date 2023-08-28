@extends('layout.master')
@section('title', auth()->user()->name)
@section('content')

    <div>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">{{ __('default.users') }} | <small>{{ auth()->user()->name }}</small></h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <a href="{{ url()->previous() }}" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                    <i class="mdi mdi-arrow-left"></i>
                    {{ __('default.back') }}
                </a>
            </div>
        </div>

        <div class="row inbox-wrapper">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="media d-block d-sm-flex">
                                    <img src="{{ optional(auth()->user()->profilePicture)->full_url ?? asset('/images/avatar.png') }}"
                                         class="wd-100p mb-3 mb-sm-0 align-self-start mr-3 wd-sm-150 img-thumbnail rounded-circle"
                                         alt="user">
                                    <div class="media-body pl-3 pt-4">
                                        <h4 class="mt-0">{{ auth()->user()->name }}</h4>
                                        <h4 class="badge badge-pill badge-success">Active</h4>
                                        <p class="text-muted">{{ auth()->user()->email }}</p>
                                        <p>{{ optional(auth()->user()->role)->name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="media d-block d-sm-flex border-left">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="media-body pl-3 pt-4">
                                                <div
                                                    class="d-flex justify-content-between align-items-center pb-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <figure class="mr-3 mb-0">
                                                            <img src="{{ asset('/images/icons/email-icon.png') }}"
                                                                 class="img-sm rounded-circle" alt="profile">
                                                        </figure>
                                                        <div>
                                                            <p class="text-muted tx-13">{{ __t('email') }} :</p>
                                                            <h6>{{ auth()->user()->email ?? __t('not_added_yet') }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="media-body pl-3 pt-4">
                                                <div
                                                    class="d-flex justify-content-between align-items-center pb-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <figure class="mr-3 mb-0">
                                                            <img src="{{ asset('/images/icons/phone-icon.png') }}"
                                                                 class="img-sm rounded-circle" alt="profile">
                                                        </figure>
                                                        <div>
                                                            <p class="text-muted tx-13">{{ __t('phone_number') }} :</p>
                                                            <h6>{{ auth()->user()->phone_number ?? __t('not_added_yet') }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <div class="media d-block d-sm-flex border-left">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="media-body pl-3 pt-4">
                                                <div
                                                    class="d-flex justify-content-between align-items-center pb-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <figure class="mr-3 mb-0">
                                                            <img src="{{ asset('/images/icons/location-icon.png') }}"
                                                                 class="img-sm rounded-circle" alt="profile">
                                                        </figure>
                                                        <div>
                                                            <p class="text-muted tx-13">{{ __t('address') }} :</p>
                                                            <h6>{{ auth()->user()->address ?? __t('not_added_yet') }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="media-body pl-3 pt-4">
                                                <div
                                                    class="d-flex justify-content-between align-items-center pb-2 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <figure class="mr-3 mb-0">
                                                            <img src="{{ asset('/images/icons/calendar-icon.png') }}"
                                                                 class="img-sm rounded-circle" alt="profile">
                                                        </figure>
                                                        <div>
                                                            <p class="text-muted tx-13">{{ __t('created_at') }} :</p>
                                                            <h6>
                                                                {{ auth()->user()->created_at->format(format_date()) }}
                                                                {{ auth()->user()->created_at->format(format_time()) }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row inbox-wrapper mt-3">
            <div class="col-sm-12 col-md-12 col-lg-3">
                <div class="card w-100 h-100 d-inline-block">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 email-aside">
                                <div class="aside-content">
                                    <div class="aside-header">
                                    <span class="title text-center">
                                        <i data-feather="user"></i>
                                    </span>
                                    </div>
                                    <div class="aside-compose"></div>
                                    <div class="aside-nav collapse">
                                        <ul class="nav">
                                            <li class="{{ request()->get('type') == 'personal_info' ? 'active' : '' }}">
                                                <a href="{{ route('profile') }}?type=personal_info">
                                                    <span class="icon"><i
                                                            data-feather="user"></i></span> {{ __t('personal_info') }}
                                                </a>
                                            </li>
                                            <li class="{{ request()->get('type') == 'password_change' ? 'active' : '' }}">
                                                <a href="{{ route('profile') }}?type=password_change">
                                                    <span class="icon"><i
                                                            data-feather="key"></i></span> {{ __t('password_change') }}
                                                </a>
                                            </li>
                                            <li class="{{ request()->get('type') == 'activity_log' ? 'active' : '' }}">
                                                <a href="{{ route('profile') }}?type=activity_log">
                                                    <span class="icon"><i
                                                            data-feather="activity"></i></span> {{ __t('activity_log') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            @if(request()->filled('type') && request()->get('type') === 'personal_info')
                @include('pages.profile._personal_info')
            @elseif(request()->filled('type') && request()->get('type') === 'password_change')
                @include('pages.profile._password_change')
            @endif

        </div>
    </div>

@endsection
@push('style')
    <style>
        .inbox-wrapper .email-aside .aside-content .aside-nav .nav li.active a {
            color: #727cf5;
            background: rgb(106 51 255 / 10%);
        }

        .inbox-wrapper .email-aside .aside-content .aside-nav .nav li.active a .icon {
            color: #727cf5;
        }
    </style>
@endpush
@push('custom-scripts')
    <script>
        $(document).ready(function (){
            $(".file-upload-browse").on('click', function (){
                $('.file-upload-default').trigger('click')
                $('.file-upload-default').change(function(){
                    const file = this.files[0];
                    $(".file-upload-info").val(file?.name ?? '')
                    if (file){
                        let reader = new FileReader();
                        reader.onload = function(event){
                            $('#imgPreview').attr('src', event.target.result);
                        }
                        reader.readAsDataURL(file);
                    }else {
                        $('#imgPreview').attr('src', "{{ asset('/images/avatar.png') }}");
                    }
                });
            })
        })
    </script>
@endpush

