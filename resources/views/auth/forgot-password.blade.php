@extends('layouts-xo.app')

@section('control_content')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto align-items-center w-50">
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
            </form>
        </div>
    </div>
@endsection