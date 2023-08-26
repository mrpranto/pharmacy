@extends('layout.master')
@section('title', __t('categories'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['products', 'categories']])
@endsection
@section('content')
    <category-list-component :permission="{{ json_encode($permission) }}"></category-list-component>
@endsection

