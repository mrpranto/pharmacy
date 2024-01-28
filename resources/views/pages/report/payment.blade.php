@extends('layout.master')
@section('title', __t('payment') .' '.__t('reports'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['reports', 'payment']])
@endsection
@section('content')

    <payment-report-component />

@endsection
