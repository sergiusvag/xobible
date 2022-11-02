@extends('layouts-xo.menu')

@php
    $isInRoom = false;
@endphp

@auth
    @php
        $room = App\Models\Room::where('host_id', Auth::user()->id)
                        ->orWhere('join_id', Auth::user()->id)
                        ->first();

        $isInRoom =  $room !== null;
    @endphp
@endauth

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
            <a href="{{ '/game/' . $locale }}" class="btn btn-welcome-begin @php echo $isInRoom ? 'control-btn-dis' : '' @endphp">{{ __('Begin game') }}</a>
        </div>
    @auth
        <div class="text-center mt-3">
            <a href="{{ '/online-room/' . $locale }}" class="btn btn-welcome-online-game">{{ $isInRoom ? __('Back to game') : __('Online game') }}</a>
        </div>
        <div class="text-center mt-3">
            <a href="{{ '/suggestion/' . $locale }}" class="btn btn-welcome-suggest @php echo $isInRoom ? 'control-btn-dis' : '' @endphp">{{ __('Suggest a question') }}</a>
        </div>
        <div class="text-center mt-3">
            <a href="{{ '/mistake/' . $locale }}" class="btn btn-welcome-mistake @php echo $isInRoom ? 'control-btn-dis' : '' @endphp">{{ __('Report a mistake') }}</a>
        </div>
    @endauth
@endsection