@extends('layout.master')
@section('title', __t('customers'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['peoples', 'customers']])
@endsection
@section('content')

    <customer-list-component :permission="{{ json_encode($permission) }}"></customer-list-component>

@endsection

