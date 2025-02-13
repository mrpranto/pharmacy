@extends('layout.master')
@section('title', 'Setting')
@section('content')
    <div class="row inbox-wrapper">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-3 email-aside border-lg-right">
                            <div class="aside-content">
                                <div class="aside-header">
                                    <span class="title text-center">
                                        <i data-feather="settings"></i> {{ __t('setting') }}
                                    </span>
                                </div>
                                <div class="aside-compose"></div>
                                <div class="aside-nav collapse">
                                    <ul class="nav">
                                        <li class="{{ request()->get('type') == 'app_setting' ? 'active' : '' }}">
                                            <a href="{{ route('setting') }}?type=app_setting">
                                                <span class="icon"><i data-feather="grid"></i></span> {{ __t('app_setting') }}
                                            </a>
                                        </li>
                                        <li class="{{ request()->get('type') == 'general' ? 'active' : '' }}">
                                            <a href="{{ route('setting') }}?type=general">
                                                <span class="icon"><i data-feather="globe"></i></span> {{ __t('general') }}
                                            </a>
                                        </li>
                                        <li class="{{ request()->get('type') == 'notification' ? 'active' : '' }}">
                                            <a href="{{ route('setting') }}?type=notification">
                                                <span class="icon"><i data-feather="bell"></i></span> {{ __t('notification') }}
                                            </a>
                                        </li>
                                        <li class="{{ request()->get('type') == 'system' ? 'active' : '' }}">
                                            <a href="{{ route('setting') }}?type=system">
                                                <span class="icon"><i data-feather="airplay"></i></span> {{ __t('system') }}
                                            </a>
                                        </li>
                                        <li class="{{ request()->get('type') == 'backup' ? 'active' : '' }}">
                                            <a href="{{ route('setting') }}?type=backup">
                                                <span class="icon"><i data-feather="download-cloud"></i></span> {{ __t('backup') }}
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
                        @elseif(request()->get('type') == 'notification')
                            @include('pages.settings._notification')
                        @elseif(request()->get('type') == 'system')
                            @include('pages.settings._system')
                        @elseif(request()->get('type') == 'backup')
                            @include('pages.settings._backup')
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
