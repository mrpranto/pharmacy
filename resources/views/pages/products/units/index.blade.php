@extends('layout.master')
@section('title', __t('units'))
@section('content')
    @include('layout.breadcrumb',['paths' => ['products', 'units']])

    <unit-list-component :permission="{{ json_encode($permission) }}"></unit-list-component>
@endsection

