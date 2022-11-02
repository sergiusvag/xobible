@extends('layouts-xo.game')

@section('control_content')
    @php
        $locale = app()->getLocale();
    @endphp
    @auth

        @csrf
        <div>Hello</div>
    @endauth
@endsection
@section('control_js')
    @vite('resources/js/app-online.js')
@endsection
