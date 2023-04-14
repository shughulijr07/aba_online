@extends('layouts.nonavbar')

@section('content')
    <div class="row" id="welcome-message">
        <div class="col-md-12 text-center">
            <h5>{{ __('Please Complete The Form Below To Create Account') }}</h5>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="row" style="margin: 8px 0 10px 0; border-top: 1px solid #ECECEC"></div>

            <form method="POST" action="{{ route('register') }}" class="row">
                @csrf

                <div class="form-group col-md-6">
                    <label for="name" class="control-label">{{ __('Full Name') }}</label>
                    <div class="inputGroupContainer">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></i></span>
                            </div>
                            <input id="name" type="text"  name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        </div>

                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="email" class="control-label">{{ __('E-Mail Address') }}</label>
                    <div class="inputGroupContainer">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input  id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email">
                        </div>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="password" class="control-label">{{ __('Password') }}</label>
                    <div class="inputGroupContainer">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input id="password" type="password"  name="password"  class="form-control @error('password') is-invalid @enderror"  required autocomplete="new-password">
                        </div>

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="password-confirm" class="control-label">{{ __('Confirm Password') }}</label>
                    <div class="inputGroupContainer">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input id="password-confirm" type="password"  name="password_confirmation"  class="form-control @error('password') is-invalid @enderror"  required  autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-12" style="margin-top:10px !important;">
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row" style="margin: 8px 0 10px 0; border-top: 1px solid #ECECEC"></div>

            <div class="row justify-content-center text-center" style="margin-bottom: 20px;">
                <div class="col-md-2">
                    <a  href="http://www.gs1tz.org">{{ __('Home') }}</a>
                </div>
                <div class="col-md-5">
                    <a href="{{ route("login") }}"> {{ __("Already Have Account? Login") }}</a>
                </div>
            </div>

        </div>
    </div>
@endsection
