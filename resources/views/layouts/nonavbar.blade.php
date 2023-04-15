<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/png" href="{{asset('/images/icon.png')}}"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nonavbar.css') }}" rel="stylesheet" type="text/css" >

</head>
<body>
<div id="app" style="margin-top: 7%;">
    <main >
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5" id="login-container">

                    <div class="row mt-2">
                        <div class="col-md-12 text-center" style="margin-bottom: 10px;">
                            <img src="{{asset('/images/tmarc_logo2.png')}}" alt="">
                        </div>
                    </div>

                    @yield('content')

                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
