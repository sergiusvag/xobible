@extends('layouts-xo.app')

@section('control_content')
    <div class="row align-items-center justify-content-center pt-1">
        <div class="page-error col-auto align-items-center w-50 text-center">
            <h2 class="page-error__not-found {{ app()->getLocale()==='he' ? 'page-error__not-found-rtl' : '' }} text-center">{{ __('403 Unauthorized action') }}</h2>
        </div>
    </div>
@endsection