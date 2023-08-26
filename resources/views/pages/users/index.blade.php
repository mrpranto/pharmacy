@extends('layout.master')
@section('title', __t('users'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['users', 'users']])
@endsection
@section('content')
    <user-list-component :permission="{{ json_encode($permission) }}"></user-list-component>
@endsection
