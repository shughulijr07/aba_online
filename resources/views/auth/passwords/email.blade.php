@extends('layouts.nonavbar')

@section('content')
    <div class="row" id="welcome-message">
        <div class="col-md-12 text-center">
            <h5>{{ __('Reset Password') }}</h5>
        </div>
    </div>
    <div class="row justify-content-center">

        <div class="col-md-10">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="row">
                @csrf

                <div class="form-group col-md-12">
                    <label for="email" class="control-label">{{ __('E-Mail Address') }}</label>
                    <div class="inputGroupContainer">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input  id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        </div>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-md-12" style="margin-top:10px !important; margin-bottom: 10px !important;">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row" style="margin: 8px 0 10px 0; border-top: 1px solid #ECECEC"></div>

            <div class="row justify-content-center text-center" style="margin-bottom: 20px;">
                <div class="col-md-6">
                    <span class="left-with-boundary"><a href="http://www.gs1tz.org">{{ __('Home') }}</a></span>
                    <span class="middle-without-boundary"><a href="{{ route("login") }}"> {{ __("Login") }}</a></span>
                    <span class="right-with-boundary"><a href="{{ route("register") }}"> {{ __("Register") }}</a></span>
                </div>
            </div>
        </div>
    </div>
@endsection
