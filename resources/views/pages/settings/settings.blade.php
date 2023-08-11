@extends('layout.master')
@section('title', 'Setting')
@section('content')
    <div class="row inbox-wrapper">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 email-aside border-lg-right">
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
                                    </ul>
                                </div>
                            </div>
                        </div>


                        @if(request()->get('type') == 'general')
                            @include('pages.settings._general_setting')
                        @elseif(request()->get('type') == 'app_setting')
                            @include('pages.settings._app_setting')
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
