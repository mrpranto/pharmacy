@extends('layout.master')
@section('title', __t('units'))
@section('content')
    <unit-list-component :permission="{{ json_encode($permission) }}"></unit-list-component>
@endsection

