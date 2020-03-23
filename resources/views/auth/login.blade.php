@extends('layouts.login')

<!-- page css -->
<link href="{{ asset('css/pages/login-register-lock.css') }}" rel="stylesheet">

@section('content')
    <section id="wrapper">
        <div class="login-register"
             style="background-image:url({{ asset('assets/images/background/login-register.jpg') }});">
            <div class="login-box card">
                <div class="card-body">
                    <form method="POST" class="form-horizontal form-material" id="loginform"
                          action="{{ route('login') }}">
                        @csrf

                        <h3 class="box-title m-b-20">@lang('Sign In')</h3>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input id="email" type="email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                       value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required
                                       autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password" placeholder="{{ __('Password') }}" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="checkbox checkbox-info pull-left p-t-0">
                                            <input id="checkbox-signup" type="checkbox"
                                                   class="filled-in chk-col-light-blue"
                                                   name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label for="checkbox-signup"> {{ __('Remember Me') }} </label>
                                        </div>
                                        {{--<a id="to-recover" class="text-dark pull-right"
                                           href="{{ route('password.request') }}"><i
                                                    class="fa fa-lock m-r-5"></i> {{ __('Forgot pwd?') }} </a>--}}</div>
                                </div>
                                <div class="form-group text-center">
                                    <div class="col-xs-12 p-b-20">
                                        <button type="submit" class="btn btn-block btn-lg btn-info btn-rounded">
                                            {{ __('Login') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" id="recoverform" action="index.html">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>Recover Password</h3>
                                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" placeholder="Email"></div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light"
                                        type="submit">Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
            </label>
        </div>
    </div>
</div>

<div class="form-group row mb-0">
    <div class="col-md-8 offset-md-4">
        <button type="submit" class="btn btn-primary">
{{ __('Login') }}
            </button>

            <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
            </a>
        </div>
    </div>
</form>
</div>
</div>
</div>
</div>
</div> -!>
@endsection
