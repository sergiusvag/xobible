@extends('layouts-xo.menu')

@section('control_content_menu')
    <h3 class="text-center">{{ __('Welcome') }}</h3>
    @php
        $locale = app()->getLocale();
    @endphp
    @auth
        <form method="POST" class="text-center mt-4" action="{{ route('logout').'/'.$locale }}">
            @csrf
            <span class="user-profile-text g-label logged-user username">{{ Auth::user()->name }}</span>
            <button type="submut"  class="btn btn-logout">{{ __('Logout') }}</button>
        </form>
    @else
        <div class="text-center mt-4">
            <a href="{{ '/login/' . $locale }}" class="btn mt-3 mt-sm-0">{{ __('Login') }}</a>
            <a href="{{ '/register/' . $locale }}" class="btn mt-3 mt-sm-0">{{ __('Register') }}</a>
        </div>
    @endauth
        <div class="text-center mt-4">
            <a href="{{ '/game/' . $locale }}" class="btn">{{ __('Begin game') }}</a>
        </div>
    @auth
        <div class="text-center mt-3">
            <a href="{{ '/online-room/' . $locale }}" class="btn">{{ __('Online game') }}</a>
        </div>
        <div class="text-center mt-3">
            <a href="{{ '/suggestion/' . $locale }}" class="btn">{{ __('Suggest a question') }}</a>
        </div>
        <div class="text-center mt-3">
            <a href="{{ '/mistake/' . $locale }}" class="btn">{{ __('Report a mistake') }}</a>
        </div>
    @endauth
@endsection