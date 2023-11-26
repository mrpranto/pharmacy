@extends('layout.master')
@section('title', __t('summary'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['reports', 'summary']])
@endsection
@section('content')

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-8 offset-xl-2 mb-3">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                        <div class="d-flex align-items-center">
                            <i data-feather="check-circle" class="icon-md text-primary mr-2"></i>
                            <p>{{ __t('al_time_revenue') }}</p>
                        </div>
                        <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($profit['revenue']) }}</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                        <div class="d-flex align-items-center">
                            <i data-feather="dollar-sign" class="icon-md text-primary mr-2"></i>
                            <p>{{ __t('al_time_expense') }}</p>
                        </div>
                        <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($profit['expense']) }}</div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                        <div class="d-flex align-items-center">
                            <p class="h4"><i data-feather="credit-card" class="icon-md text-primary mr-2"></i>{{ __t('all_time_profit') }}</p>
                        </div>
                        <div class="mr-3 h4 font-weight-lighter"> {{ show_currency($profit['profit']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-3 grid-margin grid-margin-xl-0 stretch-card">
            <div class="card radius-20">
                <div class="card-body">
                    <h6 class="card-title"><i class="mdi mdi-cart"></i> {{ __t('purchase_receive_summary') }}</h6>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <canvas id="purchaseChartjsPie"></canvas>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 pt-5">
                            <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_grand_total') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($purchase['totalGrandTotal']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_sub_total') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($purchase['totalSubTotal']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_discount') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($purchase['totalDiscount']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_paid_amount') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($purchase['totalPaid']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_due_amount') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($purchase['totalDue']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_purchase_quantity') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ $purchase['totalPurchaseQuantity'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-3 grid-margin grid-margin-xl-0 stretch-card">
            <div class="card radius-20">
                <div class="card-body">
                    <h6 class="card-title"><i class="mdi mdi-shopping"></i> {{ __t('sales_receive_summary') }}</h6>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <canvas id="saleschartjsPie"></canvas>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 pt-5">
                            <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_grand_total') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($sales['totalGrandTotal']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_sub_total') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($sales['totalSubTotal']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_discount') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($sales['totalDiscount']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_paid_amount') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($sales['totalPaid']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_due_amount') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ show_currency($sales['totalDue']) }}</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>{{ __t('total_sales_quantity') }}</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> {{ $sales['totalSalesQuantity'] }}</div>
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

            if ($('#purchaseChartjsPie').length) {
                new Chart($('#purchaseChartjsPie'), {
                    type: 'pie',
                    data: {
                        labels: ["{{ __t('total_grand_total') }}", "{{ __t('total_sub_total') }}", "{{ __t('total_discount') }}", "{{ __t('total_paid_amount') }}", "{{ __t('total_due_amount') }}", "{{ __t('total_purchase_quantity') }}"],
                        datasets: [{
                            label: "Purchase Receive Summary",
                            backgroundColor: ["#727cf5", "#7987a1", "#42b72a", "#68afff", "#fbbc06", "#ff3366"],
                            data: [{{ $purchase['totalGrandTotal'] }}, {{ $purchase['totalSubTotal'] }}, {{ $purchase['totalDiscount'] }}, {{ $purchase['totalPaid'] }}, {{ $purchase['totalDue'] }}, {{ $purchase['totalPurchaseQuantity'] }}]
                        }]
                    }
                });
            }

            if ($('#saleschartjsPie').length) {
                new Chart($('#saleschartjsPie'), {
                    type: 'pie',
                    data: {
                        labels: ["{{ __t('total_grand_total') }}", "{{ __t('total_sub_total') }}", "{{ __t('total_discount') }}", "{{ __t('total_paid_amount') }}", "{{ __t('total_due_amount') }}", "{{ __t('total_sales_quantity') }}"],
                        datasets: [{
                            label: "Sales Delivery Summary",
                            backgroundColor: ["#fbbc06", "#ff3366","#ececec", "#282f3a", "#686868", "#4d8af0"],
                            data: [{{ $sales['totalGrandTotal'] }}, {{ $sales['totalSubTotal'] }}, {{ $sales['totalDiscount'] }}, {{ $sales['totalPaid'] }}, {{ $sales['totalDue'] }}, {{ $sales['totalSalesQuantity'] }}]
                        }]
                    }
                });
            }

        });
    </script>
@endpush
