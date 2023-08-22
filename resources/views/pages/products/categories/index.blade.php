@extends('layout.master')
@section('title', __t('categories'))
@section('content')
    @include('layout.breadcrumb',['paths' => ['products', 'categories']])

    <category-list-component :permission="{{ json_encode($permission) }}"></category-list-component>
@endsection

