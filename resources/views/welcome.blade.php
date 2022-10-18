@extends('layouts-xo.app')

@section('control_content')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto user-login-buttons">
            <h3 class="text-center">{{ __('Welcome') }}</h3>
            @auth
                
                    <form method="POST" class="text-center mt-4" action="{{ route('logout') }}">
                        @csrf
                        <span class="user-profile-text g-label logged-user username">{{ Auth::user()->name }}</span>
                        <button type="submut"  class="btn">{{ __('Logout') }}</button>
                    </form>
                @else
                    <div class="text-center mt-4">
                        <a href="/login" class="btn">{{ __('Login') }}</a>
                        <a href="/register" class="btn">{{ __('Register') }}</a>
                    </div>
            @endauth
        </div>
    </div>
    <div class="row align-items-center justify-content-center mt-4">
        <div class="col-auto">
            <a href="{{ url()->current().'/game' }}" class="btn">{{ __('Begin game') }}</a>
        </div>
    </div>
    @auth
        <div class="row align-items-center justify-content-center mt-3">
            <div class="col-auto">
                <a href="{{ url()->current().'/online-room' }}" class="btn">{{ __('Online game') }}</a>
            </div>
        </div>
    <div class="row align-items-center justify-content-center mt-3">
        <div class="col-auto">
            <a href="{{ url()->current().'/suggestion' }}" class="btn">{{ __('Suggest a question') }}</a>
        </div>
    </div>
    <div class="row align-items-center justify-content-center mt-3">
        <div class="col-auto">
                <a href="{{ url()->current().'/mistake' }}" class="btn">{{ __('Report a mistake') }}</a>
        </div>
    </div>
    @endauth
@endsection