<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
    <div id='app'>123</div>
    @vite('resources/js/app.js')
</body>
</html>
<!-- 
@section('control_content')
    <div class="row align-items-center justify-content-center pt-3">
        <div class="col-auto align-items-center w-50 text-center">
            @auth
                @csrf
                <h3 class="text-center">{{ __('Online Room') }}</h3>
                    <div class="row mt-4">
                        <div class="col-12 col-md-9 col-lg-6 m-auto text-center">
                            <button type="submut" class="btn test">{{ __('Test Button') }}</button>
                        </div>
                    </div>
            @endauth
        </div>
    </div>
@endsection -->