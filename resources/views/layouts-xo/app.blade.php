<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('components.head')

<body>
    <section id="main-screen" class="main-screen d-flex align-items-center">
		<div class="container">
            @yield('control_content')
		</div>
	</section>
    @vite('resources/js/app.js')
</body>
</html>