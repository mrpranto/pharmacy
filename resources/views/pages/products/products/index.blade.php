@extends('layout.master')
@section('title', __t('products'))
@section('content')
    <product-list-component :permission="{{ json_encode($permission) }}"></product-list-component>

@endsection
