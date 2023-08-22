@extends('layout.master')
@section('title', __t('users'))

@section('content')
    @include('layout.breadcrumb',['paths' => ['users', 'users']])

    <user-list-component :permission="{{ json_encode($permission) }}"></user-list-component>
@endsection
