@extends('layout.master')
@section('title', __t('sales') .' '.__t('reports'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['reports', 'sales']])
@endsection
@section('content')

    <sales-report-component :customers="{{ json_encode($customers) }}" />

@endsection
