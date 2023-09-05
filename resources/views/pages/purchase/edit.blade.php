@extends('layout.master')
@section('title', __t('edit_purchase'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['purchase', 'edit_purchase']])
@endsection
@section('content')
    <edit-purchase-component :id="{{ $id }}"></edit-purchase-component>
@endsection

