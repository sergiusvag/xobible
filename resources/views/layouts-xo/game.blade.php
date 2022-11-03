<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('components.head')
@include('components.loader')

<body>
    <section id="main-screen" class="main-screen d-flex align-items-center">
		<div class="container">
            <div class="row align-items-center justify-content-center pt-3">
                <div class="col-3 d-none d-lg-block align-items-center text-center">
                    @yield('control_left_side')
                </div>
                <div class="col-auto col-md-12 col-lg-6 align-items-center text-center">
                    @yield('control_content')
		        </div>
                <div class="col-3 d-none d-lg-block align-items-center text-center">
                    @yield('control_right_side')
                </div>
		    </div>
		</div>
	</section>
    @yield('control_js')
</body>
</html>