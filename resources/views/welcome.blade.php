@extends('layouts-xo.menu')

@section('control_content_menu')
    <h3 class="text-center">{{ __('Welcome') }}</h3>
    @auth
        <form method="POST" class="text-center mt-4" action="{{ route('logout').'/'.$locale }}">
            @csrf
            <span class="user-profile-text">{{ Auth::user()->name }}</span>
            <button type="submut"  class="btn btn-logout">{{ __('Logout') }}</button>
        </form>
    @else
        <div class="text-center mt-4">
            <a href="{{ '/login/' . $locale }}" class="btn mt-3 mt-sm-0">{{ __('Login') }}</a>
            <a href="{{ '/register/' . $locale }}" class="btn mt-3 mt-sm-0">{{ __('Register') }}</a>
        </div>
    @endauth
        <div class="text-center mt-4">
            <a href="{{ '/offline-color-picker/' . $locale }}" class="btn btn-pos-action btn-welcome-begin {{ $data['btnClass'] }}">{{ __('Begin game') }}</a>
        </div>
    @auth
        <div class="text-center mt-3">
            <form action="{{ $data['hostOrJoinExitLink'] }}" method="post">
                @csrf
                <a href="{{ $data['btnUrl'] }}" class="btn btn-welcome-online-game btn-pos-action">{{ $data['btnText'] }}</a>
                @php
                    if($data['isInRoom']) {
                @endphp
                        <button type="submit" class="btn btn-exit-room">{{ __('Exit game') }}</button>
                        <input type="text" class="form-post-input" id="room_number" name="room_number" value="{{ $data['room_number'] }}" hidden><br>
                        <input type="text" class="form-post-input" id="redirect" name="redirect" value="{{ '/welcome/'. $locale }}" hidden><br>
                @php
                    }
                @endphp
            </form>
        </div>
        <div class="text-center mt-3">
            <a href="{{ '/suggestion/' . $locale }}" class="btn btn-pos-action btn-welcome-suggest">{{ __('Suggest a question') }}</a>
        </div>
        <div class="text-center mt-3">
            <a href="{{ '/mistake/' . $locale }}" class="btn btn-pos-action btn-welcome-mistake">{{ __('Report a mistake') }}</a>
        </div>
    @endauth
@endsection