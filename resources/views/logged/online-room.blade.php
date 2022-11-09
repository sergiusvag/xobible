@extends('layouts-xo.menu')

@section('control_content_menu')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto align-items-center text-center">
            @php
                $locale = app()->getLocale();
            @endphp
            @auth
                @csrf
                <h3 class="text-center">{{ __('Online Room') }}</h3>

                <label class="locale" hidden>{{ app()->getLocale() }}</label>
                
                <div class="row creation-controlls mt-3">
                    
                    <div class="col">
                        <div class="row">
                            <div class="col mt-sm-0 mt-1">
                                <button type="button" class="btn btn-room-create">{{ __('Create') }}</button>
                                <button type="button" class="btn btn-room-close d-none">{{ __('Close') }}</button>
                            </div>
                            <div class="col mt-sm-0 mt-1">
                                <button type="button" class="btn btn-room-join">{{ __('Enter') }}</button>
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
                                <label class="room-label room-label-msg room-label-success mt-2 v-hidden">Success joining room</label>
                            </div>
                        </div>
                        <a href="{{ '/welcome/' . app()->getLocale() }}" class="btn btn-room-back mt-3">{{ __('Back') }}</a>
                        <div class="in-room-controlls v-hidden">
                            <div class="row mt-4 usernames">
                                <div class="col-6">
                                    <span class="user-profile-text g-label logged-user username username-host v-hidden">Host Name</span>
                                </div>
                                <div class="col-6">
                                    <span class="user-profile-text g-label logged-user username username-join v-hidden">Joinie Name</span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 holder-btn-room-start v-hidden">
                                    <button type="button" class="btn btn-room-start">{{ __('Start') }}</button>
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