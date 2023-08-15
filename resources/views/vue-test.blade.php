@extends('layout.master')
@section('title', 'Vue-test')
@section('content')

    @foreach($files as $file)
        <a href="{{ $file }}"  download="{{ $file }}">{{ $file }}</a> <br>
    @endforeach

@endsection
