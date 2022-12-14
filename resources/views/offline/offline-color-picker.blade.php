@extends('layouts-xo.game')

@section('control_content')
    @php
        $locale = app()->getLocale();
    @endphp
    @include('components.color-picker')
    
@endsection

@section('control_js')
    @vite('resources/js/app-offline-color-picker.js')
@endsection
