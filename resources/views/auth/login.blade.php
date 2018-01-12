@extends('layout')

@section('content')
<div class="container js-form-container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('auth.login.login')</div>
                <div class="panel-body">
                    @if ($site)
                        <p class="bold">{{ $site->title }} @lang('auth.login.shipments_assets_access_login')</p>
                        <p>@lang('auth.login.form_to_gain_access') {{ $site->title }} @lang('auth.login.shipments_and_assets_data').</p>
                    @endif
                    @if (!$site && ContextHelper::isAdminContext($context))
                        <p class="bold">@lang('auth.login.admin_login')</p>
                    @endif
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        @if (session('status'))
                            <div class="alert alert-success animate">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->has('failed'))
                            <div class="alert alert-danger animate">
                                <strong>{{ $errors->first('failed') }}</strong>
                            </div>
                        @endif

                        @if ($errors->has('lockout'))
                            <div class="alert alert-danger animate">
                                <strong>{{ $errors->first('lockout') }}</strong>
                            </div>
                        @endif

                        @if ($errors->has('disabled'))
                            <div class="alert alert-danger animate">
                                <strong>{{ $errors->first('disabled') }}</strong>
                            </div>
                        @endif

                        @if ($errors->has('access_denied'))
                            <div class="alert alert-danger animate">
                                <strong>{{ $errors->first('access_denied') }}</strong>
                            </div>
                        @endif

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label colon-after">@lang('auth.login.email_address')</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} margin-bottom-none">
                            <label for="password" class="col-md-4 control-label colon-after">@lang('auth.login.password')</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control js-password-input" name="password">
                                <div class="toggle-password-display-container js-toggle-password-display-container">
                                    <a class="js-toggle-password-display" data-password-display="show"><i class="fa fa-eye"></i> @lang('auth.login.show_password')</a>
                                    <a class="js-toggle-password-display" data-password-display="hide" style="display:none;"><i class="fa fa-eye-slash"></i> @lang('auth.login.hide_password')</a>
                                </div>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> @lang('auth.login.remember_me')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group margin-bottom-none">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary single-click">
                                    <i class="fa fa-btn fa-sign-in"></i> @lang('auth.login.login')
                                </button>
                                <div class="margin-top-md">
                                    <a href="{{ route('password.reset') }}">@lang('auth.login.forgot_password')</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
