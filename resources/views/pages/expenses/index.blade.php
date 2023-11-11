@extends('layout.master')
@section('title', __t('expenses'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['expenses', 'expenses_list']])
@endsection
@section('content')

    <expenses-list-component :permission="{{ json_encode($permission) }}"></expenses-list-component>

@endsection

