@extends('layout.master')
@section('title', 'Dashboard')
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['dashboard']])
@endsection
@push('style')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"/>
@endpush

@section('content')

    <div class="row">
        <div class="col-lg-6 col-xl-6">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <h6 class="card-title mb-0"><i class="mdi mdi-calendar-multiple-check"></i> Weekly sales &
                            Purchase</h6>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex justify-content-between mr-3">
                                <div style="height: 10px;width: 10px;background-color: #282f3a;margin: 4px"></div>
                                <span>Sales</span>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div style="height: 10px;width: 10px;background-color: #7987a1;margin: 4px"></div>
                                <span>Purchase</span>
                            </div>
                        </div>
                    </div>
                    <div class="monthly-sales-chart-wrapper">
                        <canvas id="monthly-sales-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-6">
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

    <div class="row">
        <div class="col-lg-7 col-xl-7">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <h6 class="card-title mb-0">
                            <i class="mdi mdi-timetable"></i> Recent Sales
                        </h6>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped border">
                                <thead>
                                <tr>
                                    <th>{{ __t('sl') }}</th>
                                    <th>{{ __t('invoice_number') }}</th>
                                    <th>{{ __t('invoice_date') }}</th>
                                    <th>{{ __t('customer') }}</th>
                                    <th>{{ __t('grand_total') }}</th>
                                    <th>{{ __t('payment_status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-xl-5">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <h6 class="card-title mb-0">
                            <i class="mdi mdi-account-star"></i> Top Customer
                        </h6>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped border">
                                <thead>
                                <tr>
                                    <th>{{ __t('customer') }}</th>
                                    <th class="text-right">{{ __t('grand_total') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
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

{{--@dd($weeklyChart)--}}

@push('custom-scripts')
    {{--    <script src="{{ asset('assets/js/dashboard.js') }}"></script>--}}
    <script>
        $(function () {
            'use strict'
            var gridLineColor = 'rgba(77, 138, 240, .1)';
            var colors = {
                primary: "#727cf5",
                secondary: "#7987a1",
                success: "#42b72a",
                info: "#68afff",
                warning: "#fbbc06",
                danger: "#ff3366",
                light: "#ececec",
                dark: "#282f3a",
                muted: "#686868"
            }
            // Monthly sales chart start
            if ($('#monthly-sales-chart').length) {
                var monthlySalesChart = document.getElementById('monthly-sales-chart').getContext('2d');
                new Chart(monthlySalesChart, {
                        type: 'bar',
                        data: {
                            labels: [
                                @foreach($weeklyChart['labels'] as $key => $label)
                                    '{{ $label }}'{{ count($weeklyChart['labels']) == ($key+1) ?'': ',' }}
                                    @endforeach
                            ],
                            datasets: [{
                                label: 'Sales',
                                data: [
                                    @foreach($weeklyChart['weeklySale'] as $key => $sale)
                                        {{ $sale }}{{ count($weeklyChart['labels']) == ($key+1) ?'': ',' }}
                                        @endforeach
                                ],
                                backgroundColor: colors.dark
                            }, {
                                label: 'Purchase',
                                data: [
                                    @foreach($weeklyChart['weeklyPurchase'] as $key => $purchase)
                                        {{ $purchase }}{{ count($weeklyChart['labels']) == ($key+1) ?'': ',' }}
                                        @endforeach
                                ],
                                backgroundColor: colors.secondary
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            legend: {
                                display: false,
                                labels: {
                                    display: false
                                }
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    barPercentage: .3,
                                    categoryPercentage: .6,
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: {
                                        fontColor: '#8392a5',
                                        fontSize: 10
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        color: gridLineColor
                                    },
                                    ticks: {
                                        fontColor: '#8392a5',
                                        fontSize: 10,
                                        min: 100,
                                        max: {{ $weeklyChart['max'] }}
                                    }
                                }]
                            }
                        }
                    }
                );
            }
        });
    </script>
@endpush
