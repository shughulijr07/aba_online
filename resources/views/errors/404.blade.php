<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/png" href="/images/icon.png"/>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/404.css') }}" rel="stylesheet">

</head>
<body>
<div class="noise"></div>
<div class="overlay"></div>
<div class="terminal">
    <h1>Error <span class="errorcode">404</span></h1>
    <p class="output">The page you are looking for might have been removed, had its name changed or is temporarily unavailable.</p>
    <p class="output">Please try to <a href="{{url()->previous()}}">go back</a> or <a href="/">return to the homepage</a>.</p>
    <p class="output">Good luck.</p>
</div>
</body>
</html>
