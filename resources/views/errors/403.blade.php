<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/png" href="/images/icon.png"/>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/403.css') }}" rel="stylesheet">

</head>
<body>
<div class="wrapper">
    <div class="box">
        <h1>403</h1>
        <p>{{$exception->getMessage()}}</p>
        <p><a href="{{url()->previous()}}">Please, go back this way.</a></p>
    </div>
</div>
</body>
</html>
