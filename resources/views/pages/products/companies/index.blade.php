@extends('layout.master')
@section('title', __t('companies'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['products', 'companies']])
@endsection
@section('content')
    <company-list-component :permission="{{ json_encode($permission) }}"></company-list-component>
@endsection

