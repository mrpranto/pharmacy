@extends('layout.master')
@section('title', __t('products'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['products', 'products']])
@endsection
@section('content')

    <product-list-component :permission="{{ json_encode($permission) }}"></product-list-component>

@endsection
