@extends('layout.master')
@section('title', __t('roles'))
@section('content')
    <role-list-component :permission="{{ json_encode($permission) }}"></role-list-component>
@endsection

