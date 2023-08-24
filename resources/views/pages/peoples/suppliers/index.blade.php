@extends('layout.master')
@section('title', __t('suppliers'))
@section('content')
    @include('layout.breadcrumb',['paths' => ['peoples', 'suppliers']])

    <supplier-list-component :permission="{{ json_encode($permission) }}"></supplier-list-component>
@endsection

