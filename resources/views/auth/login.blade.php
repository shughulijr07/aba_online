@extends('layouts.nonavbar')

@section('content')

    <!-- it there is ongoing session logout first-->
    {{ auth()->logout() }}

    <div class="row" id="welcome-message">
        <div class="col-md-12 text-center">
            <h6>{{ __('Welcome, Please Login To Continue') }}</h6>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="control-label">{{ __('Username') }}</label>
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

                <div class="form-group">
                    <label for="password" class="control-label">{{ __('Password') }}</label>
                    <div class="inputGroupContainer">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input id="password" type="password"  name="password"  class="form-control @error('password') is-invalid @enderror"  required autocomplete="current-password">
                        </div>

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row" style="margin-top:30px !important;">
                    <div class="col-md-6 ">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6 ">
                        <button type="submit" class="btn btn-primary btn-block">
                            {{ __('Login') }}
                        </button>

                    </div>
                </div>

            </form>

            <div class="row" style="margin: 8px 0 10px 0; border-top: 1px solid #ECECEC"></div>

            <div class="row text-center" style="margin-bottom: 20px;">
                <div class="col-md-12 text-center">
                    In case you have forgotten your password, please contact system administrator.
                </div>
            </div>

        </div>
    </div>
@endsection
