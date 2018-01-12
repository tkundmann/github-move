@extends('layout')

@section('content')
<div class="container js-form-container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('auth.password.reset_password')</div>

                <div class="panel-body">

                    @if (session('status'))
                        <div class="alert alert-success animate">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (isset($prevalidateResult) && !empty($prevalidateResult))
                        <div class="alert alert-danger margin-bottom-none animate">
                            {{ trans($prevalidateResult) }}
                        </div>
                    @else
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('password.reset.reset') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label colon-after">@lang('auth.password.email_address')</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label colon-after">@lang('auth.password.password')</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control js-password-input" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password-confirm" class="col-md-4 control-label colon-after">@lang('auth.password.confirm_password')</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control js-password-input" name="password_confirmation">
                                    <div class="toggle-password-display-container js-toggle-password-display-container">
                                        <a class="js-toggle-password-display" data-password-display="show"><i class="fa fa-eye"></i> @lang('auth.login.show_passwords')</a>
                                        <a class="js-toggle-password-display" data-password-display="hide" style="display:none;"><i class="fa fa-eye-slash"></i> @lang('auth.login.hide_passwords')</a>
                                    </div>

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary single-click">
                                        <i class="fa fa-btn fa-refresh"></i> @lang('auth.password.reset_password')
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
