@extends('layouts-xo.app')

@section('control_left_side')
    <div class="left-wrap">
        <div class="x-line x-line_1">
            <div class="x-line__line x-line__line_1"></div>						
        </div>
        <div class="x-line x-line_2">
            <div class="x-line__line x-line__line_2"></div>	
        </div>
    </div>
@endsection

@section('control_content')
    @yield('control_content_menu')
@endsection

@section('control_right_side')
    <div class="right-wrap">
        <div class="circle">
            <div class="half-wrap half-wrap-left">
                <div class="circle__half left-half"></div>
            </div>
            <div class="half-wrap half-wrap-right">
                <div class="circle__half right-half"></div>
            </div>
        </div>						
    </div>
@endsection
