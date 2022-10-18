@extends('layouts-xo.app')

@section('control_content')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto align-items-center user-login">
            <h3 class="login-header text-center">{{ __('Sign in') }}</h3>
            <form method="POST" action="{{ route('login') }}">
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
                
                <div class="login-group mt-4 text-center">
                    <button type="submit" class="btn">{{ __('Log in') }}</button>
                </div>
                <div class="login-group mt-3 text-center">
                    @if (Route::has('password.request'))
                        <a class="login-forgot-password extra-link" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection