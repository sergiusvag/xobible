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
            <a href="{{ '/game/' . $locale }}" class="btn btn-welcome-begin @php echo $isInRoom ? 'control-btn-dis' : '' @endphp">{{ __('Begin game') }}</a>
        </div>
    @auth
        <div class="text-center mt-3">
            @php
                if($isInRoom) {
                    $roomOrGame = $room['status'] === 'in_game' ? 'game' : 'room';
                    $url = '/online-' . $roomOrGame. '/' . $locale;
                    $btnText = __('Back to game');
                } else {
                    $url = '/online-room/' . $locale;
                    $btnText = __('Online game');
                }
                @endphp
                    <a href="{{ $url }}" class="btn btn-welcome-online-game">{{ $btnText }}</a>
                @php
            @endphp
        </div>
        <div class="text-center mt-3">
            <a href="{{ '/suggestion/' . $locale }}" class="btn btn-welcome-suggest @php echo $isInRoom ? 'control-btn-dis' : '' @endphp">{{ __('Suggest a question') }}</a>
        </div>
        <div class="text-center mt-3">
            <a href="{{ '/mistake/' . $locale }}" class="btn btn-welcome-mistake @php echo $isInRoom ? 'control-btn-dis' : '' @endphp">{{ __('Report a mistake') }}</a>
        </div>
    @endauth
@endsection