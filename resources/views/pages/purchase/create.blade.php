@extends('layout.master')
@section('title', __t('add_purchase'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['purchase', 'add_purchase']])
@endsection
@section('content')
    <add-new-purchase-component></add-new-purchase-component>
@endsection

