@extends('layouts-xo.app')

@section('control_content')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto align-items-center w-50 text-center">
            @auth

                <h3 class="text-center">{{ __('Report a mistake') }}</h3>
                <div class="user-profile-text text-center">
                    {{ __('Found a mistake in one of the questions? Please, let us know!') }}
                </div>
                <div class="user-profile-text text-center mt-1">
                    {{ __('We have provided for you a template of the mistake description for your convenience.') }}
                </div>
                <form id="form-submit-mistake" method="POST" action="{{ url()->current() }}">
                    @csrf
                    <div class="mt-4 w-25 m-auto">
                        <input type="number" name="question_id" class="form-control input-field text-center input-group" id="question_id" placeholder="{{ __('Question №') }}" 
                        @if (session('question_id'))
                            value="{{ session('question_id') }}"
                        @endif
                        required>
                    </div>
                    @if (session('question'))
                        <div class="col-9 mt-2 input-msg input-error-msg m-auto">
                            {{ session('question') }}
                        </div>
                    @elseif (session('success'))
                        <div class="col-9 mt-2 input-msg input-success-msg m-auto">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="text-start mt-3">
                        <textarea name="mistake" rows="5" class="input-field input-field-textarea input-group" id="mistake" required>@if (session('mistake'))
{{ session('mistake') }}
                        @else
{{ __('I found a mistake in: Question / Option № : 1,2,3,4 / Answer / Location') }}
{{ __('There is a mistake in the word:') }}
{{ __('The correct option is wrong, it should be № x and not № y') }}
{{ __('The answer location is wrong, the correct one is:') }}@endif</textarea>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 col-md-9 col-lg-6 m-auto text-center">
                            <button type="submut" class="btn">{{ __('Submit mistake') }}</button>
                            <a href="/" class="btn mt-3">{{ __('Back') }}</a>
                        </div>
                    </div>
                </form>
            @endauth
        </div>
    </div>
@endsection