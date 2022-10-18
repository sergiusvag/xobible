@extends('layouts-xo.app')

@section('control_content')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto align-items-center user-register">
            <h3 class="register-header text-center">{{ __('Register') }}</h3>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="register-group mt-4">
                    <input type="text" name="name" class="form-control input-field text-center input-group" id="name" placeholder="{{ __('Name') }}">
                </div>
                    @if ($errors->get('name'))
                        <div class="col mt-2 input-msg input-error-msg text-center m-auto">
                            {{ __('Please enter your name') }}
                        </div>
                    @endif
                <div class="register-group mt-3">
                    <input type="email" name="email" class="form-control input-field text-center input-group" id="email" placeholder="{{ __('Email') }}">
                </div>
                    @if ($errors->get('email'))
                        <div class="col mt-2 input-msg input-error-msg text-center m-auto">
                            {{ __('Please enter correct email') }}
                        </div>
                    @endif
                <div class="register-group mt-3">
                    <input type="password" name="password" class="form-control input-field text-center input-group" id="password" placeholder="{{ __('Password') }}">
                </div>
                <div class="register-group mt-3">
                    <input type="password" name="password_confirmation" class="form-control input-field text-center input-group" id="password_confirmation" placeholder="{{ __('Confirm Password') }}">
                </div>
                    @if ($errors->get('password') || $errors->get('password_confirmation'))
                        <div class="col mt-2 input-msg input-error-msg text-center m-auto">
                            {{ __('Weak password, or passwords do not match') }}
                        </div>
                    @endif
                
                <div class="register-group mt-4">
                    <button type="submit" class="btn">{{ __('Register') }}</button>
                </div>
                <div class="register-group mt-3 m-auto text-center">
                    <a class="register-already-registered extra-link" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection