@extends('layouts-xo.menu')

@section('control_content_menu')
    @auth

        <h3 class="text-center">{{ __('Report a mistake') }}</h3>
        <div class="user-profile-text text-center">
            {{ __('Found a mistake in one of the questions? Please, let us know!') }}
        </div>
        <div class="user-profile-text text-center mt-1">
            {{ __('We have provided for you a template of the mistake description for your convenience.') }}
        </div>
        <form id="form-submit-mistake" method="POST" action="{{ url()->current() }}" class="{{ $rtlClass }}">
            @csrf
            <div class="mt-4 w-25 m-auto">
                <input type="number" name="question_id" class="form-control input-field input-field-bg text-center input-group" id="question_id" placeholder="{{ __('Question №') }}" 
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
                <textarea name="mistake" rows="5" class="input-field input-field-bg input-field-textarea input-group" id="mistake" required>@if (session('mistake'))
{{ session('mistake') }}
                @else
{{ __('I found a mistake in: Question / Option № : 1,2,3,4 / Answer / Location') }}
{{ __('There is a mistake in the word:') }}
{{ __('The correct option is wrong, it should be № x and not № y') }}
{{ __('The answer location is wrong, the correct one is:') }}@endif</textarea>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-md-9 col-lg-6 m-auto text-center">
                    <button type="submut" class="btn btn-pos-action mt-1">{{ __('Submit mistake') }}</button>
                    <a href="{{ '/welcome/' . app()->getLocale() }}" class="btn mt-1">{{ __('Back') }}</a>
                </div>
            </div>
        </form>
    @endauth
@endsection