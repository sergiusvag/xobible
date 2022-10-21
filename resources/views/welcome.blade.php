@extends('layouts-xo.menu')

@section('control_content_menu')
            <h3 class="text-center">{{ __('Welcome') }}</h3>
            @auth
                <form method="POST" class="text-center mt-4" action="{{ route('logout') }}">
                    @csrf
                    <span class="user-profile-text g-label logged-user username">{{ Auth::user()->name }}</span>
                    <button type="submut"  class="btn btn-logout">{{ __('Logout') }}</button>
                </form>
            @else
                <div class="text-center mt-4">
                    <a href="/login" class="btn mt-3 mt-sm-0">{{ __('Login') }}</a>
                    <a href="/register" class="btn mt-3 mt-sm-0">{{ __('Register') }}</a>
                </div>
            @endauth
                <div class="text-center mt-4">
                    <a href="{{ url()->current().'/game' }}" class="btn mt-4">{{ __('Begin game') }}</a>
                </div>
            @auth
                <div class="text-center mt-3">
                    <a href="{{ url()->current().'/online-room' }}" class="btn mt-3">{{ __('Online game') }}</a>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ url()->current().'/suggestion' }}" class="btn mt-3">{{ __('Suggest a question') }}</a>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ url()->current().'/mistake' }}" class="btn mt-3">{{ __('Report a mistake') }}</a>
                </div>
            @endauth
@endsection