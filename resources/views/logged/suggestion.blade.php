@extends('layouts-xo.app')

@section('control_content')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto align-items-center w-50 text-center">
            @auth

                <h3 class="text-center">{{ __('Suggest a question') }}</h3>
                <div class="user-profile-text text-center mt-3">
                    {{ __('You have an idea for a question? Send it to us and we will add it to the game!') }}
                </div>
                <div class="user-profile-text text-center mt-1">
                    {{ __('You do not have to think of all the options, but it will make our job easier 💕') }}
                </div>
                <form id="form-submit-suggestion" method="POST" action="{{ url()->current() }}">
                    @csrf
                    @if (session('success'))
                        <div class="col-9 mt-2 input-msg input-success-msg m-auto">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="mt-4 m-auto">
                        <input type="text" name="question" class="form-control input-field input-group" id="question" placeholder="{{ __('Question') }}" required>
                    </div>
                    <div class="mt-3 m-auto">
                        <input type="text" name="option_1" class="form-control input-field input-group" id="option_1" placeholder="{{ __('Option') }} 1">
                    </div>
                    <div class="mt-3 m-auto">
                        <input type="text" name="option_2" class="form-control input-field input-group" id="option_2" placeholder="{{ __('Option') }} 2">
                    </div>
                    <div class="mt-3 m-auto">
                        <input type="text" name="option_3" class="form-control input-field input-group" id="option_3" placeholder="{{ __('Option') }} 3">
                    </div>
                    <div class="mt-3 m-auto">
                        <input type="text" name="option_4" class="form-control input-field input-group" id="option_4" placeholder="{{ __('Option') }} 4">
                    </div>
                    <div class="mt-3 m-auto">
                        <input type="text" name="answer" class="form-control input-field input-group" id="answer" placeholder="{{ __('Answer') }}" required>
                    </div>
                    <div class="mt-3 m-auto">
                        <input type="text" name="location" class="form-control input-field input-group" id="location" placeholder="{{ __('Location') }}" required>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 col-md-9 col-lg-6 m-auto text-center">
                            <button type="submut" class="btn">{{ __('Submit suggestion') }}</button>
                            <a href="/" class="btn mt-3">{{ __('Back') }}</a>
                        </div>
                    </div>
                </form>
            @endauth
        </div>
    </div>
@endsection