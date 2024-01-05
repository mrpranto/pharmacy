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
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                        <div class="pl-4">
                            <p><i class="mdi mdi-calendar"></i> {{ __t('today_sales') }}</p>
                            <h4 class="mt-2 font-weight-light">
                                <span>{{ show_currency($todaySales) }}</span> <span class="ml-4"><i
                                        data-feather="trending-up"></i></span>
                            </h4>
                            <p class="mt-2 font-weight-light small">* {{ __t('update_every_order') }}.</p>
                        </div>
                        <div class="list-card-icon">
                            <h1><i class="mdi mdi-shopping text-primary"></i></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                        <div class="pl-4">
                            <p><i class="mdi mdi-calendar"></i> {{ __t('today_earning') }}</p>
                            <h4 class="mt-2 font-weight-light">
                                {{ show_currency($todayEarning) }} <span class="ml-4"><i data-feather="trending-up"></i></span>
                            </h4>
                            <p class="mt-2 font-weight-light small">* {{ __t('update_every_order') }}.</p>
                        </div>
                        <div class="list-card-icon">
                            <h1><i class="mdi mdi-wallet text-primary"></i></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                        <div class="pl-4">
                            <p><i class="mdi mdi-calendar"></i> {{ __t('today_expense') }}</p>
                            <h4 class="mt-2 font-weight-light">
                                {{ show_currency($todayExpense) }} <span class="ml-4"><i
                                        data-feather="trending-down"></i></span>
                            </h4>
                            <p class="mt-2 font-weight-light small">* {{ __t('update_every_expense') }}.</p>
                        </div>
                        <div class="list-card-icon">
                            <h1><i class="mdi mdi-currency-usd text-primary"></i></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                        <div class="pl-4">
                            <p><i class="mdi mdi-calendar-text"></i> {{ __t('total_orders') }}</p>
                            <h4 class="mt-2 font-weight-light">
                                {{ show_currency($totalSales) }} <span class="ml-4"><i
                                        data-feather="activity"></i></span>
                            </h4>
                            <p class="mt-2 font-weight-light small">* {{ __t('all_time_order') }}.</p>
                        </div>
                        <div class="list-card-icon">
                            <h1><i class="mdi mdi-truck-delivery text-primary"></i></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                        <div class="pl-4">
                            <p><i class="mdi mdi-calendar-text"></i> {{ __t('total_purchase') }}</p>
                            <h4 class="mt-2 font-weight-light">
                                {{ show_currency($totalPurchase) }} <span class="ml-4"><i
                                        data-feather="activity"></i></span>
                            </h4>
                            <p class="mt-2 font-weight-light small">* {{ __t('all_time_purchase') }}.</p>
                        </div>
                        <div class="list-card-icon">
                            <h1><i class="mdi mdi-cart text-primary"></i></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center" style="height: 130px">
                        <div class="pl-4">
                            <p><i class="mdi mdi-calendar-text"></i> {{ __t('total_available_stocks') }}</p>
                            <h4 class="mt-2 font-weight-light">
                                {{ show_currency($totalCurrentStockValue) }} <span class="ml-4"><i
                                        data-feather="bar-chart-2"></i></span>
                            </h4>
                            <p class="mt-2 font-weight-light small">* {{ __t('current_available_stock') }}.</p>
                        </div>
                        <div class="list-card-icon">
                            <h1><i class="mdi mdi-database text-primary"></i></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-3 grid-margin grid-margin-xl-0 stretch-card">
            <div class="card radius-20">
                <div class="card-body">
                    <h6 class="card-title mb-0">
                        <i class="mdi mdi-calendar-multiple-check"></i> {{ __t('half_monthly_revenue') }}
                    </h6>
                    <canvas id="monthly-sales-chart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-3">
            <div class="card radius-20 w-100 h-100 d-inline-block">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <h6 class="card-title mb-0">
                            <i class="mdi mdi-account-star"></i> {{ __t('top_customer') }}
                        </h6>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <canvas id="chartjsPolarArea"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card radius-20 w-100 h-100 d-inline-block">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <h6 class="card-title mb-0">
                            <i class="mdi mdi-timetable"></i> {{ __t('recent_sales') }}
                        </h6>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
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
                                            <td>
                                                <a href="{{ route('invoice-pdf', $sale->id) }}" target="_blank">
                                                    {{ $sale->invoice_number }} <i class="mdi mdi-arrow-top-right"></i>
                                                </a>
                                            </td>
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
        </div>
    </div>

@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/chartjs/Chart.min.js') }}"></script>
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

            /*
            * Weekly Sales revenue
            */
            if ($('#monthly-sales-chart').length) {
                new Chart($('#monthly-sales-chart'), {
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
                                backgroundColor: colors.primary
                            }]
                        },

                    }
                );
            }


            /*
            * Top 5 customer
            */
            if ($('#chartjsPolarArea').length) {
                new Chart($('#chartjsPolarArea'), {
                    type: 'polarArea',
                    data: {
                        labels: [
                            @foreach($customers as $key => $customer)
                                '{{ $customer['name'] }}'{{ count($customers) == ($key+1) ?'': ',' }}
                                @endforeach
                        ],
                        datasets: [
                            {
                                label: "Top 5 Customer",
                                backgroundColor: ["#6495ED", "#9FE2BF", "#40E0D0", "#7987a1", "#CCCCFF"],
                                data: [
                                    @foreach($customers as $key => $customer)
                                        '{{ $customer['totalGrandTotal'] }}'{{ count($customers) == ($key+1) ?'': ',' }}
                                        @endforeach
                                ]
                            }
                        ]
                    }
                });
            }
        });
    </script>
@endpush
