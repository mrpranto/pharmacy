@extends('layout.master')
@section('title', __t('summary'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['reports', 'summary']])
@endsection
@section('content')

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-3 grid-margin grid-margin-xl-0 stretch-card">
            <div class="card radius-20">
                <div class="card-body">
                    <h6 class="card-title"><i class="mdi mdi-cart"></i> Purchase Receive Summary</h6>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-8">
                            <canvas id="purchaseChartjsPie"></canvas>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4 pt-5">
                            <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Total Grand Total</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Total Sub Total</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Total Discount </p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Paid Amount </p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Due Amount </p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Purchase Quantity </p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 mb-3 grid-margin grid-margin-xl-0 stretch-card">
            <div class="card radius-20">
                <div class="card-body">
                    <h6 class="card-title"><i class="mdi mdi-shopping"></i> Sales Delivery Summary</h6>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <canvas id="saleschartjsPie"></canvas>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Total Grand Total</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Total Sub Total</p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Total Discount </p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Paid Amount </p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Due Amount </p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i data-feather="chevrons-right" class="icon-md text-primary mr-2"></i>
                                    <p>Sales Quantity </p>
                                </div>
                                <div class="mr-3 h5 font-weight-lighter"> $123123</div>
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
                        labels: ["Grand Total", "Sub Total", "Total Discount", "Total Paid", "Total Due", "Total Purchase Quantity"],
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
                        labels: ["Africa", "Asia", "Europe"],
                        datasets: [{
                            label: "Population (millions)",
                            backgroundColor: ["#7ee5e5", "#f77eb9", "#4d8af0"],
                            data: [2478, 4267, 1334]
                        }]
                    }
                });
            }

        });
    </script>
@endpush
