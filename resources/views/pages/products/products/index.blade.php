@extends('layout.master')
@section('title', __t('products'))
@section('content')
    @include('layout.breadcrumb',['paths' => ['products', 'products']])

    <product-list-component :permission="{{ json_encode($permission) }}"></product-list-component>

@endsection
