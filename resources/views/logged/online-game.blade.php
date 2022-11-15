@extends('layouts-xo.game')

@auth
@section('control_left_side')
<div class="player-score-wrap">
    <label class="user-profile-text">Sergey</label>
    <label class="round-score"></label>
</div>
@endsection

@section('control_content')
    @php
        $locale = app()->getLocale();
    @endphp
        @csrf
        <div class="game">GAME</div>
    
@endsection

@section('control_right_side')
<div class="host_score">SCORE</div>
@endsection

@endauth
@section('control_js')
    @vite('resources/js/app-online-game.js')
@endsection
