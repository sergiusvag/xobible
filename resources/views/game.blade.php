@extends('layouts-xo.game')

@section('control_left_side')
<div class="player-score-wrap wrap-host wrap-host-game-main">
    <label class="score-txt score-txt name-host">{{ __('Player One') }}</label>
    <div class="row mt-4 score-txt-holder">
        <label class="col-12 score-txt">{{ __('Correct : ') }}<span class="score-correct-round-host"></span></label>
    </div>
    <div class="row mt-1 score-txt-holder">
        <label class="col-12 score-txt">{{ __('Bonus : ') }}<span class="score-bonus-round-host"></span></label>
    </div>
    <div class="row mt-1 score-txt-holder">
        <label class="col-12 score-txt">{{ __('Wrong : ') }}<span class="score-wrong-round-host"></span></label>
    </div>
    <div class="row mt-3 score-txt-holder">
        <label class="col-12 score-txt">{{ __('Total : ') }}<span class="score-total-round-host"></span></label>
    </div>
    <div class="row mt-5 score-txt-holder">
        <label class="col-12 score-txt">{{ __('Game total : ') }}<span class="score-total-game-host"></span></label>
    </div>
</div>
@endsection

@section('control_content')
    @php
        $locale = app()->getLocale();
    @endphp
        @csrf
        <label class="locale" hidden>{{ $locale }}</label>
        <label class="question_category_id" hidden>{{ $data['question_category_id'] }}</label>
        @if($data['isOnline'])
        <label class="room_number" hidden>{{ $data['room_number'] }}</label>
        <label class="current_round" hidden>{{ $data['current_round'] }}</label>
        <label class="max_round" hidden>{{ $data['max_round'] }}</label>
        @else
        <label class="host_color" hidden>{{ $data['host_color'] }}</label>
        <label class="join_color" hidden>{{ $data['join_color'] }}</label>
        @endif
        @include('components.game.rules')
        @include('components.game.round')
        @include('components.game.board')
        @include('components.game.question')
        @include('components.game.result')
        @include('components.game.over')
        <button class="btn btn-rules btn-rules-turn-on mt-1">{{ __('Rules') }}</button>
@endsection

@section('control_right_side')
<div class="player-score-wrap border-right wrap-join wrap-join-game-main">
    <label class="score-txt border-right name-join">{{ __('Player Two') }}</label>
    <div class="row mt-4 score-txt-holder">
        <label class="col-12 score-txt border-right">{{ __('Correct : ') }}<span class="score-correct-round-join"></span></label>
    </div>
    <div class="row mt-1 score-txt-holder">
        <label class="col-12 score-txt border-right">{{ __('Bonus : ') }}<span class="score-bonus-round-join"></span></label>
    </div>
    <div class="row mt-1 score-txt-holder">
        <label class="col-12 score-txt border-right">{{ __('Wrong : ') }}<span class="score-wrong-round-join"></span></label>
    </div>
    <div class="row mt-3 score-txt-holder">
        <label class="col-12 score-txt border-right">{{ __('Total : ') }}<span class="score-total-round-join"></span></label>
    </div>
    <div class="row mt-5 score-txt-holder">
        <label class="col-12 score-txt border-right">{{ __('Game total : ') }}<span class="score-total-game-join"></span></label>
    </div>

</div>
@endsection

@section('control_js')
    @if($data['isOnline'])
        @vite('resources/js/app-online-game.js')
    @else
        @vite('resources/js/app-offline-game.js')
    @endif
@endsection
