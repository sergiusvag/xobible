@extends('layouts-xo.app')

@section('control_content')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto align-items-center w-50">
            <div class="user-profile-text text-center">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>
            @if (session('status') == 'verification-link-sent')
                <div class="user-profile-text text-center">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div class="mt-4 m-auto text-center">
                    <button type="submit" class="btn">{{ __('Resend Verification Email') }}</button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <div class="mt-3 m-auto text-center">
                    <button type="submut"  class="extra-link">{{ __('Logout') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection