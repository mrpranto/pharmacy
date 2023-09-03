@extends('layout.master')
@section('title', __t('purchase_list'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['purchase', 'purchase_list']])
@endsection
@section('content')
    <purchase-list-component :permission="{{ json_encode($permission) }}"></purchase-list-component>
@endsection

