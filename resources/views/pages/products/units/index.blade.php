@extends('layout.master')
@section('title', __t('units'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['products', 'units']])
@endsection
@section('content')
    <unit-list-component :permission="{{ json_encode($permission) }}"></unit-list-component>
@endsection

