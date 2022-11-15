@extends('layouts-xo.menu')

@section('control_content_menu')
    <h3 class="login-header text-center">{{ __('Sign in') }}</h3>
    <form method="POST" action="{{ route('login').'/'.app()->getLocale() }}">
        @csrf
        <div class="login-group mt-4">
            <input type="email" name="email" class="form-control input-field text-center input-group" id="email" placeholder="{{ __('Email') }}">
        </div>
        <div class="login-group mt-3">
            <input type="password" name="password" class="form-control input-field text-center input-group" id="password" placeholder="{{ __('Password') }}">
        </div>
        @if ($errors->get('email'))
            <div class="col mt-2 input-msg input-error-msg text-center m-auto">
                {{ __('Wrong credentials') }}
            </div>
        @endif
        
        <div class="login-group mt-3 text-center">
            @if (Route::has('password.request'))
                <a class="login-forgot-password extra-link" href="{{ route('password.request').'/'.app()->getLocale() }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
        <div class="login-group mt-4 text-center">
            <button type="submit" class="btn btn-pos-action">{{ __('Log in') }}</button>
        </div>
        @include('components.back-button')
    </form>
@endsection