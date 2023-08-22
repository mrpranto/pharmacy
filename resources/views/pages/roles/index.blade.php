@extends('layout.master')
@section('title', __t('roles'))
@section('content')
    @include('layout.breadcrumb',['paths' => ['users', 'roles']])

    <role-list-component :permission="{{ json_encode($permission) }}"></role-list-component>
@endsection

