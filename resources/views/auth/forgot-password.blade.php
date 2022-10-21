@extends('layouts-xo.menu')

@section('control_content_menu')
    <div class="user-profile-text text-center">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>
    <form method="POST" class="align-items-center justify-content-center text-center" action="{{ route('password.email') }}">
        @csrf
        <div class="forgot-password mt-4">
            <input type="email" name="email" class="form-control input-field text-center input-group" id="email" placeholder="{{ __('Email') }}">
        </div>
            @if ($errors->get('email'))
                <div class="col mt-2 input-msg input-error-msg text-center m-auto">
                    {{ __('Wrong email') }}
                </div>
            @endif
        <button type="submit" class="btn mt-4">{{ __('Reset Password') }}</button>
        <div class="col-12 col-md-9 col-lg-6 m-auto text-center">
            <a href="/" class="btn mt-2">{{ __('Back') }}</a>
        </div>
    </form>
@endsection