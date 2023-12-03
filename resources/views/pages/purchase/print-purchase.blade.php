@extends('layout.master')
@section('title', 'INV-'.$purchase->reference)
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['purchase', 'print']])
@endsection
@push('style')
    <style>
        @media print {
            body {
                visibility: hidden;
            }
            #printarea {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
@endpush
@section('content')

    <div class="row mb-3 d-print-none">
        <div class="col-lg-12">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-3 mb-md-0">{{ __('default.print') }} {{ __('default.purchase_details') }}</h5>
                        </div>

                        <div class="d-flex align-items-center flex-wrap text-nowrap">
                            <button class="btn btn-primary float-right mr-2" onclick="printPurchase()">
                                <i class="mdi mdi-printer"></i> {{ __('default.print') }}
                            </button>

                            <button class="btn btn-danger float-right" onclick="window.close()">
                                <i class="mdi mdi-backspace"></i> {{ __('default.close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-12" id="printarea">
            <div class="card radius-20">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="container-fluid mt-3 d-flex justify-content-between">
                                <div class="col-lg-6 pl-0">
                                    <h5 class="mb-2 text-muted">{{ __('default.supplier') }} : {{
                                    $purchase->supplier['name']
                                }}</h5>
                                    <p>{{ __('default.phone_number') }} : {{ $purchase->supplier['phone_number'] }},
                                        {{ __('default.email') }} : {{ $purchase->supplier['email'] }}</p>
                                    <p>{{ $purchase->supplier['address'] }}</p>
                                </div>
                                <div class="col-lg-6 pr-0">
                                    <h6 class="text-right pb-4"># INV-{{ $purchase->reference }}</h6>
                                    <h4 class="text-right font-weight-normal">{{ __('default.total') }}:
                                        {{ show_currency($purchase->total) }}</h4>
                                    <h6 class="mb-0 mt-3 text-right font-weight-normal mb-2">
                                        <p class="mb-2"><span class="text-muted">{{ __('default.date') }} :</span>
                                            {{ date(format_date(), strtotime($purchase->date)) }}</p>
                                        <p class="mb-2">
                                            <span class="text-muted">{{ __('default.status') }} : </span>
                                            @if($purchase->status == 'received')
                                                <span>{{ strtoupper($purchase->status) }}</span>
                                            @elseif($purchase->status == 'pending')
                                                <span>{{ strtoupper($purchase->status) }}</span>
                                            @else
                                                <span>{{ strtoupper($purchase->status) }}</span>
                                            @endif
                                        </p>
                                        <p class="mb-2">
                                            <span class="text-muted">{{ __('default.payment_status') }} : </span>
                                            <span>{{ strtoupper($purchase->payment_status) }}</span>
                                        </p>
                                    </h6>
                                </div>
                            </div>
                            <div class="container-fluid mt-3 d-flex justify-content-center w-100">
                                <div class="table-responsive w-100">
                                    <table class="table table-hover table-striped border">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('default.product') }}</th>
                                            <th class="text-right">{{ __('default.unit_price') }}</th>
                                            <th class="text-right">{{ __('default.sale_price') }}</th>
                                            <th class="text-right">{{ __('default.quantity') }}</th>
                                            <th class="text-right">{{ __('default.sub_total') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($purchase->purchase_products as $key => $purchase_products)
                                            <tr class="text-right" >
                                                <td class="text-left">{{ ($key + 1) }}</td>
                                                <td width="20%" class="text-left">
                                                    <div class="d-flex align-items-center">
                                                        <figure class="mb-0 mr-2">
                                                            <img width="35" :height="35"
                                                                     src="{{ $purchase_products['product']['product_photo']['full_url'] ?? '/images/medicine.png' }}"
                                                                     class="img-sm rounded-circle"
                                                                     alt="{{ $purchase_products['product']['name'] }}"/>
                                                            <div class="status online"></div>
                                                        </figure>
                                                        <div>
                                                            <p class="font-weight-bolder text-capital">
                                                                {{ $purchase_products['product']['name'] }}
                                                                <small class="text-muted" title="{{__('default.unit')}}">
                                                                    {{ $purchase_products['product']['unit']['name'] }}
                                                                    ({{ $purchase_products['product']['unit']['pack_size'] }})
                                                                </small>
                                                            </p>
                                                            <p class="text-muted tx-13"><b>{{ __('default.barcode') }}: </b>
                                                                {{ $purchase_products['product']['barcode'] }}
                                                            </p>
                                                            <p class="text-muted tx-13">
                                                                <span title="{{__('default.company')}}">{{ $purchase_products['product']['company']['name'] }}</span>,
                                                                <span title="{{__('default.category')}}">{{ $purchase_products['product']['category']['name'] }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ show_currency($purchase_products['unit_price']) }}</td>
                                                <td>{{ show_currency($purchase_products['sale_price']) }}</td>
                                                <td>{{ $purchase_products['quantity'] }}</td>
                                                <td>
                                                    {{ show_currency($purchase_products['subTotal']) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="container-fluid mt-3 w-100">
                                <div class="row">
                                    <div class="col-md-6 ml-auto">
                                        <div class="table-responsive">
                                            <table class="table border">
                                                <tbody>
                                                <tr>
                                                    <td>{{ __('default.sub_total') }}</td>
                                                    <td class="text-right">{{ show_currency($purchase->subtotal) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('default.other_cost') }}</td>
                                                    <td class="text-right">{{ show_currency($purchase->otherCost) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>(-) {{ __('default.discount') }}</td>
                                                    <td class="text-right">{{ show_currency($purchase->discount) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-bold-800 h3">{{ __('default.total') }}</td>
                                                    <td class="text-bold-800 text-right h3">
                                                        {{ show_currency($purchase->total) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-bold-800 h3">{{ __('default.paid_amount') }}</td>
                                                    <td class="text-bold-800 text-right h3">
                                                        {{ show_currency($purchase->total_paid) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-bold-800 h3">{{ __('default.due_amount') }}</td>
                                                    <td class="text-bold-800 text-right h3">
                                                        {{ show_currency($purchase->total - $purchase->total_paid) }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
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


@endsection
@push('custom-scripts')
<script>
    function printPurchase(){
       window.print()
    }
</script>
@endpush

