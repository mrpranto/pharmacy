@extends('layout.master')
@section('title', __t('edit_sale'))
@section('breadcrumb')
    @include('layout.breadcrumb',['paths' => ['sales', 'edit_sale']])
@endsection
@section('content')

    <edit-sale-component
        :sale="{{ json_encode($sale) }}"
        :user_email="{{ json_encode(auth()->user()->email) }}"
        :categories="{{ json_encode($categories) }}"
        :companies="{{ json_encode($companies) }}" />

@endsection

