@extends('layout.master')
@section('title', __t('roles'))
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet"/>
@endpush
@section('content')
    <role-list-component :permission="{{ json_encode($permission) }}"></role-list-component>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
@endpush
