@extends('layouts-xo.game')

@section('control_content')
    @php
        $locale = app()->getLocale();
    @endphp
    @auth
        @csrf
        @include('components.color-picker')
    @endauth
    
@endsection

@section('control_js')
    @vite('resources/js/app-online-color-picker.js')
@endsection
