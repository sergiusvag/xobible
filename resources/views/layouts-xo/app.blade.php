<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('components.head')

<body>
    <section id="main-screen" class="main-screen d-flex align-items-center">
		<div class="container">
            @include('components.locale-buttons')
            <div class="row align-items-center justify-content-center pt-3">
                <div class="col-3 d-none d-md-block align-items-center text-center">
                    @yield('control_left_side')
                </div>
                <div class="col-auto col-sm-8 col-md-6 align-items-center text-center">
                    @yield('control_content')
		        </div>
                <div class="col-3 d-none d-md-block align-items-center text-center">
                    @yield('control_right_side')
                </div>
		    </div>
		</div>
	</section>
    @vite('resources/js/app.js')
</body>
</html>