@extends('layout.master')
@section('title', 'Dashboard')
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['dashboard']])
@endsection
@push('style')
    <style>
        .total-card:hover {
            box-shadow: 0.3em 0.3em 1em rgba(0, 0, 0, 0.3);
        }
    </style>
@endpush

@section('content')

<!--    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow">
                <div class="col-sm-12 col-md-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <a href="{{ route('customers.index') }}" class="text-decoration-none text-dark">
                            <div class="card-body total-card p-4">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3">{{ __t('total_customer') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $total_customer }}</h3>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7 text-right">
                                        <i data-feather="users" width="30px" height="30px" class="mr-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <a href="{{ route('suppliers.index') }}" class="text-decoration-none text-dark">
                            <div class="card-body total-card p-4">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3">{{ __t('total_supplier') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $total_supplier }}</h3>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7 text-right">
                                        <i data-feather="user-plus" width="30px" height="30px" class="mr-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <a href="{{ route('users.index') }}" class="text-decoration-none text-dark">
                            <div class="card-body total-card p-4">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3">{{ __t('total_user') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $total_user }}</h3>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7 text-right">
                                        <i data-feather="user" width="30px" height="30px" class="mr-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <a href="{{ route('products.index') }}" class="text-decoration-none text-dark">
                            <div class="card-body total-card p-4">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3">{{ __t('total_product') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $total_product }}</h3>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7 text-right">
                                        <i data-feather="briefcase" width="30px" height="30px" class="mr-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <a href="{{ route('companies.index') }}" class="text-decoration-none text-dark">
                            <div class="card-body total-card p-4">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3">{{ __t('total_company') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $total_company }}</h3>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7 text-right">
                                        <i data-feather="award" width="30px" height="30px" class="mr-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <a href="{{ route('categories.index') }}" class="text-decoration-none text-dark">
                            <div class="card-body total-card p-4">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3">{{ __t('total_category') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $total_category }}</h3>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7 text-right">
                                        <i data-feather="check-circle" width="30px" height="30px" class="mr-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <a href="{{ route('purchases.index') }}" class="text-decoration-none text-dark">
                            <div class="card-body total-card p-4">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3">{{ __t('total_purchase') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $total_purchase }}</h3>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7 text-right">
                                        <i data-feather="shopping-cart" width="30px" height="30px" class="mr-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <a href="{{ route('stocks') }}" class="text-decoration-none text-dark">
                            <div class="card-body total-card p-4">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3">{{ __t('total_stock') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $total_stock }}</h3>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7 text-right">
                                        <i data-feather="database" width="30px" height="30px" class="mr-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-sm-4 grid-margin stretch-card">
                    <div class="card">
                        <a href="" class="text-decoration-none text-dark">
                            <div class="card-body total-card p-4">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-3">{{ __t('total_sale') }}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">{{ $total_sale }}</h3>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7 text-right">
                                        <i data-feather="shopping-bag" width="30px" height="30px" class="mr-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

@endsection

@push('plugin-scripts')

@endpush

@push('custom-scripts')

@endpush
