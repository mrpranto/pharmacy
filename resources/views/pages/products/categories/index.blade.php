@extends('layout.master')
@section('title', __t('categories'))
@section('content')
    <category-list-component :permission="{{ json_encode($permission) }}"></category-list-component>
@endsection

