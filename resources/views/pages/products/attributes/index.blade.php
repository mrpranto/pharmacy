@extends('layout.master')
@section('title', __t('attributes'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['products', 'attributes']])
@endsection
@section('content')

    <attribute-list-component :permission="{{ json_encode($permission) }}"></attribute-list-component>

@endsection

