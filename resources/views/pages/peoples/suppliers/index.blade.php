@extends('layout.master')
@section('title', __t('suppliers'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['peoples', 'suppliers']])
@endsection
@section('content')
    <supplier-list-component :permission="{{ json_encode($permission) }}"></supplier-list-component>
@endsection

