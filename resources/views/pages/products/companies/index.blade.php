@extends('layout.master')
@section('title', __t('companies'))
@section('content')
    <company-list-component :permission="{{ json_encode($permission) }}"></company-list-component>
@endsection

