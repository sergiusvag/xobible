@extends('layouts-xo.menu')

@section('control_content_menu')
    <div class="user-profile-text text-center">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="register-group mt-3">
            <input type="password" name="password" class="form-control input-field text-center input-group" id="password" placeholder="{{ __('Password') }}">
        </div>
            @if ($errors->get('password'))
                <div class="col mt-2 input-msg input-error-msg text-center m-auto">
                    {{ __('Wrong password') }}
                </div>
            @endif

        <div class="mt-3 m-auto text-center">
            <button type="submut"  class="btn">{{ __('Confirm') }}</button>
        </div>
    </form>
    <div class="col-12 col-md-9 col-lg-6 m-auto text-center">
        <a href="/" class="btn mt-2">{{ __('Back') }}</a>
    </div>
@endsection