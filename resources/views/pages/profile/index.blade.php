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
                                    <img src="{{ asset('/images/avatar.png') }}"
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
                                                <div class="d-flex justify-content-between align-items-center pb-2 mb-2">
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
                                                <div class="d-flex justify-content-between align-items-center pb-2 mb-2">
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
                                                <div class="d-flex justify-content-between align-items-center pb-2 mb-2">
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
                                                <div class="d-flex justify-content-between align-items-center pb-2 mb-2">
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 email-aside border-lg-right">
                                <div class="aside-content">
                                    <div class="aside-header">
                                    <span class="title text-center">
                                        <i data-feather="user"></i>
                                    </span>
                                    </div>
                                    <div class="aside-compose"></div>
                                    <div class="aside-nav collapse">
                                        <ul class="nav">
                                            <li class="{{ request()->get('type') == 'app_setting' ? 'active' : '' }}">
                                                <a href="{{ route('setting') }}?type=app_setting">
                                                    <span class="icon"><i data-feather="user"></i></span> {{ __t('personal_info') }}
                                                </a>
                                            </li>
                                            <li class="{{ request()->get('type') == 'general' ? 'active' : '' }}">
                                                <a href="{{ route('setting') }}?type=general">
                                                    <span class="icon"><i data-feather="key"></i></span> {{ __t('password_change') }}
                                                </a>
                                            </li>
                                            <li class="{{ request()->get('type') == 'backup' ? 'active' : '' }}">
                                                <a href="{{ route('setting') }}?type=backup">
                                                    <span class="icon"><i data-feather="activity"></i></span> {{ __t('activity_log') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                            @if(request()->get('type') == 'general')
                                @include('pages.settings._general_setting')
                            @elseif(request()->get('type') == 'app_setting')
                                @include('pages.settings._app_setting')
                            @elseif(request()->get('type') == 'backup')
                                @include('pages.settings._backup')
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

