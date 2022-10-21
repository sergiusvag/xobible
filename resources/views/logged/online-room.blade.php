<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
    @vite('resources/js/app.js')
</body>
</html> -->
@extends('layouts-xo.app')

@section('control_content')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto align-items-center w-50 text-center">
            @auth
                @csrf
                <h3 class="text-center">{{ __('Online Room') }}</h3>
                <div class="row mt-4">
                    <div class="col-12 col-md-9 col-lg-6 m-auto text-center chat-group">
                        <input type="text" class="chat form-control input-field text-center input-group">
                        <button type="button" class="btn btn-chat mt-2">{{ __('Test Button') }}</button>
                        <label class="my_name" hidden>{{ Auth::user()->name }}</label>
                    </div>
                </div>
                
                <form method="POST" action="{{ url()->current() }}">
                    @csrf
                    <div class="login-group mt-4">
                        <label class="my_name-form" hidden>{{ Auth::user()->name }}</label>
                    </div>
                    <div class="login-group mt-3">
                        <input type="text" name="chat-id" class="form-control input-field text-center input-group" id="chat-id" placeholder="{{ __('Room number') }}">
                    </div>
                    
                    <div class="login-group mt-4 text-center">
                        <button type="submit" class="btn btn-room-id">{{ __('Room num button') }}</button>
                    </div>
                </form>
            @endauth
        </div>
    </div>
@endsection