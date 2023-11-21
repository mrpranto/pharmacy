@extends('layout.master')
@section('title', 'Dashboard')
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['dashboard']])
@endsection
@push('style')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

    <div class="row">
        <div class="col-lg-5 col-xl-5">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <h6 class="card-title mb-0"> <i class="mdi mdi-calendar-multiple-check"></i> Weekly sales & Purchase</h6>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex justify-content-between mr-3">
                                <div style="height: 10px;width: 10px;background-color: #282f3a;margin: 4px"></div> <span>Sales</span>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div style="height: 10px;width: 10px;background-color: #7987a1;margin: 4px"></div> <span>Purchase</span>
                            </div>
                        </div>
                    </div>
                    <div class="monthly-sales-chart-wrapper">
                        <canvas id="monthly-sales-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-xl-7">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                    <div class="card radius-20">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                                <div class="pl-4">
                                    <p><i class="mdi mdi-calendar-check"></i> Today Sales</p>
                                    <h4 class="mt-2 font-weight-light">{{ show_currency(1212) }}</h4>
                                    <p class="mt-2 font-weight-light small">* Update every order success.</p>
                                </div>
                                <div class="list-card-icon">
                                    <h1><i class="mdi mdi-shopping"></i></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                    <div class="card radius-20">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                                <div class="pl-4">
                                    <p><i class="mdi mdi-calendar-check"></i> Today Earning</p>
                                    <h4 class="mt-2 font-weight-light">{{ show_currency(1212) }}</h4>
                                    <p class="mt-2 font-weight-light small">* Update every order success.</p>
                                </div>
                                <div class="list-card-icon">
                                    <h1><i class="mdi mdi-currency-usd"></i></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                    <div class="card radius-20">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                                <div class="pl-4">
                                    <p><i class="mdi mdi-calendar-check"></i> Total orders</p>
                                    <h4 class="mt-2 font-weight-light">{{ show_currency(1212) }}</h4>
                                    <p class="mt-2 font-weight-light small">* Al time success order.</p>
                                </div>
                                <div class="list-card-icon">
                                    <h1><i class="mdi mdi-truck-delivery"></i></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                    <div class="card radius-20">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                                <div class="pl-4">
                                    <p><i class="mdi mdi-calendar-check"></i> Total purchase</p>
                                    <h4 class="mt-2 font-weight-light">{{ show_currency(1212) }}</h4>
                                    <p class="mt-2 font-weight-light small">* Al time success purchase.</p>
                                </div>
                                <div class="list-card-icon">
                                    <h1><i class="mdi mdi-cart"></i></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush
