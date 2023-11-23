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
                        <h6 class="card-title mb-0"><i class="mdi mdi-calendar-multiple-check"></i> Weekly Revenue</h6>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex justify-content-between mr-3">
                                <div style="height: 10px;width: 10px;background-color: #282f3a;margin: 4px"></div>
                                <span>Revenue</span>
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
                                    <p><i class="mdi mdi-calendar"></i> Today Sales</p>
                                    <h4 class="mt-2 font-weight-light">
                                        <span>{{ show_currency($todaySales) }}</span> <span class="ml-4"><i data-feather="trending-up"></i></span>
                                    </h4>
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
                                    <p><i class="mdi mdi-calendar"></i> Today Earning</p>
                                    <h4 class="mt-2 font-weight-light">
                                        {{ show_currency($todayEarning) }} <span class="ml-4"><i data-feather="trending-up"></i></span>
                                    </h4>
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
                                    <p><i class="mdi mdi-calendar-text"></i> Total orders</p>
                                    <h4 class="mt-2 font-weight-light">
                                        {{ show_currency($totalSales) }} <span class="ml-4"><i data-feather="activity"></i></span>
                                    </h4>
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
                                    <p><i class="mdi mdi-calendar-text"></i> Total purchase</p>
                                    <h4 class="mt-2 font-weight-light">
                                        {{ show_currency($totalPurchase) }} <span class="ml-4"><i data-feather="activity"></i></span>
                                    </h4>
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
                                    <th>{{ __t('invoice_number') }}</th>
                                    <th>{{ __t('invoice_date') }}</th>
                                    <th>{{ __t('customer') }}</th>
                                    <th>{{ __t('grand_total') }}</th>
                                    <th>{{ __t('status') }}</th>
                                    <th>{{ __t('payment_status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recent_sales as $sale)
                                <tr>
                                    <td>{{ $sale->invoice_number }}</td>
                                    <td>
                                        {{ date(format_date(), strtotime($sale->invoice_date)) }}
                                        <br>
                                        <small>{{ date(format_time(), strtotime($sale->invoice_date)) }}</small>
                                    </td>
                                    <td>
                                        {{ $sale->customer->name }}
                                        @if($sale->customer_id != 1)
                                            <br>
                                            <small>({{ $sale->customer->phone_number }})</small>
                                        @endif
                                    </td>
                                    <td>{{ show_currency($sale->grand_total) }}</td>
                                    <td>
                                        @if ($sale->status === 'CONFIRMED')
                                            <span class="badge badge-info">{{ $sale->status }}</span>
                                        @elseif ($sale->status === 'DRAFT')
                                            <span class="badge badge-warning">{{ $sale->status }}</span>
                                        @elseif ($sale->status === 'CANCELED')
                                            <span class="badge badge-danger">{{ $sale->status }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $sale->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($sale->payment_status === 'DUE')
                                            <span class="badge badge-danger"
                                                  title="{{ $sale->total_paid }}">{{ $sale->payment_status }}</span>
                                        @elseif ($sale->payment_status === 'PARTIAL-PAID')
                                            <span class="badge badge-info"
                                                  title="{{ $sale->total_paid }}">{{ $sale->payment_status }}</span>
                                        @elseif ($sale->payment_status === 'OVER-DUE')
                                            <span class="badge badge-warning"
                                                  title="{{ $sale->total_paid }}}">{{ $sale->payment_status }}</span>
                                        @else
                                            <span class="badge badge-success"
                                                  title="{{ $sale->total_paid }}">{{ $sale->payment_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
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
                                @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        {{ $customer->name }}
                                        @if($customer->id != 1)
                                            <br>
                                            <small>({{ $customer->phone_number }})</small>
                                        @endif
                                    </td>
                                    <td class="text-right">{{ show_currency($customer->totalGrandTotal) }}</td>
                                </tr>
                                @endforeach
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

@push('custom-scripts')
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
                                label: 'Revenue',
                                data: [
                                    @foreach($weeklyChart['weeklyRevenue'] as $key => $sale)
                                        {{ $sale }}{{ count($weeklyChart['labels']) == ($key+1) ?'': ',' }}
                                        @endforeach
                                ],
                                backgroundColor: colors.dark
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
