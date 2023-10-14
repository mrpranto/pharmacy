@extends('layout.master')
@section('title', __t('add_sale'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['sales', 'add_sale']])
@endsection
@section('content')

    <add-new-sale-component
        :categories="{{ json_encode($categories) }}"
        :companies="{{ json_encode($companies) }}" />

@endsection

