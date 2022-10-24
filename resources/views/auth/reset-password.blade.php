@extends('layouts-xo.menu')

@section('control_content_menu')
    <h3 class="reset-password-header text-center">{{ __('Reset Password') }}</h3>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="reset-password-group mt-4">
            <input type="email" name="email" class="form-control input-field text-center input-group" id="email" placeholder="{{ __('Email') }}" value="old('email', $request->email)">
        </div>
        @if ($errors->get('email'))
            <div class="col mt-2 input-msg input-error-msg text-center m-auto">
                {{ __('Please enter correct email') }}
            </div>
        @endif
        <div class="reset-password-group mt-3">
            <input type="password" name="password" class="form-control input-field text-center input-group" id="password" placeholder="{{ __('Password') }}">
        </div>
        <div class="reset-password-group mt-3">
            <input type="password" name="password_confirmation" class="form-control input-field text-center input-group" id="password_confirmation" placeholder="{{ __('Confirm Password') }}">
        </div>
        @if ($errors->get('password') || $errors->get('password_confirmation'))
            <div class="col mt-2 input-msg input-error-msg text-center m-auto">
                {{ __('Weak password, or passwords do not match') }}
            </div>
        @endif
        
        <button type="submit" class="btn mt-4">{{ __('Reset Password') }}</button>
    </form>
        @include('components.back-button')
@endsection