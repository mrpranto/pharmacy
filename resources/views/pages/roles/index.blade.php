@extends('layout.master')
@section('title', __t('roles'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['users', 'roles']])
@endsection
@section('content')
    <role-list-component :permission="{{ json_encode($permission) }}"></role-list-component>
@endsection

