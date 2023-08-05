@extends('layout.master')
@section('title', __t('users'))

@section('content')
    <user-list-component :permission="{{ json_encode($permission) }}"></user-list-component>
@endsection
