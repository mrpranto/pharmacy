@extends('layout.master')
@section('title', __t('companies'))
@section('content')
    @include('layout.breadcrumb',['paths' => ['products', 'companies']])

    <company-list-component :permission="{{ json_encode($permission) }}"></company-list-component>
@endsection

