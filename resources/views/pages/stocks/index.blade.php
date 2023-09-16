@extends('layout.master')
@section('title', __t('stock_list'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['stock_list']])
@endsection
@section('content')

    <stock-component />

@endsection

