@extends('layout.master')
@section('title', __t('purchase') .' '.__t('reports'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['reports', 'purchase']])
@endsection
@section('content')

    <purchase-report-component :suppliers="{{ json_encode($suppliers) }}"/>

@endsection
