@extends('layouts-xo.game')

@section('control_left_side')
@endsection

@section('control_content')
    @php
        $locale = app()->getLocale();
    @endphp
    @auth

        @csrf
    @endauth
    
@endsection

@section('control_right_side')
@endsection

@section('control_js')
    @vite('resources/js/app-online.js')
@endsection
