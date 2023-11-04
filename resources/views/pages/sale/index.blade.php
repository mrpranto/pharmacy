@extends('layout.master')
@section('title', __t('sales_list'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['sales', 'sales_list']])
@endsection
@section('content')

    <sale-list-component
        :permission="{{ json_encode($permission) }}"
        :payment_type="{{ json_encode($payment_type) }}" />

@endsection

