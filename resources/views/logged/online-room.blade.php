@extends('layouts-xo.menu')

@section('control_content_menu')
    <div class="row align-items-center justify-content-center pt-1">
        <div class="col-auto align-items-center text-center">
            @auth
                @csrf
                <h3 class="text-center">{{ __('Online Room') }}</h3>

                <label class="locale" hidden>{{ $locale }}</label>
                
                <div class="row creation-controlls mt-3">
                    
                    <div class="col">
                        <div class="row">
                            <div class="col mt-sm-0 mt-1">
                                <button type="button" class="btn btn-pos-action btn-room-create">{{ __('Create') }}</button>
                                <button type="button" class="btn btn-room-close d-none">{{ __('Close') }}</button>
                            </div>
                            <div class="col mt-sm-0 mt-1">
                                <button type="button" class="btn btn-pos-action btn-room-join">{{ __('Enter') }}</button>
                                <button type="button" class="btn btn-room-exit d-none">{{ __('Exit Room') }}</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                            <label class="room-label mt-2 mb-1">{{ __('Room key') }}</label>
                            <input type="number" class="input-room input-room-key input-field input-group form-control"></input>
                            </div>
                            <div class="col-6">
                            <label class="room-label mt-2 mb-1">{{ __('Room number') }}</label>
                            <input type="number" class="input-room input-room-number input-field input-group form-control"></input>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="room-label room-label-msg mt-2 v-hidden"></label>
                            </div>
                        </div>
                        <a href="{{ '/welcome/' . $locale }}" class="btn btn-room-back mt-3">{{ __('Back') }}</a>
                        <div class="in-room-controlls v-hidden">
                            <div class="row justify-content-center align-items-center text-center mt-4 usernames">
                                <div class="col-3">
                                    <span class="user-profile-text g-label logged-user username username-host v-hidden"></span>
                                </div>
                                <div class="col-2 room-smily-holder">
                                    <span class="room-smily room-smily-host v-hidden">💬</span>
                                    <div class="room-smily-options room-smily-options_host v-hidden">
                                        <span class="room-smily-option" data-room-message="Hello! 👋">👋</span>
                                        <span class="room-smily-option" data-room-message="Let's go! 😁">😁</span>
                                        <span class="room-smily-option" data-room-message="Are you ready? ❓">❓</span>
                                    </div>
                                </div>
                                <div class="col-2 room-smily-holder">
                                    <span class="room-smily room-smily-join v-hidden">💬</span>
                                    <div class="room-smily-options room-smily-options_join v-hidden">
                                        <span class="room-smily-option" data-room-message="Hello! 👋">👋</span>
                                        <span class="room-smily-option" data-room-message="I am Ready! 😁">😁</span>
                                        <span class="room-smily-option" data-room-message="Please wait! ⛔">⛔</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <span class="user-profile-text g-label logged-user username username-join v-hidden"></span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 holder-btn-room-start v-hidden">
                                    <button type="button" class="btn btn-pos-action btn-room-start">{{ __('Start') }}</button>
                                </div>
                                <div class="col-6 holder-btn-room-kick v-hidden">
                                    <button type="button" class="btn btn-room-kick">{{ __('Kick') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
@endsection

@section('control_js')
    @vite('resources/js/app.js')
@endsection