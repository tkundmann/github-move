@extends('layout')

@section('content')
<div class="container js-form-container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('auth.password.change_password')</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.change') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
                            <label for="old_password" class="col-md-4 control-label colon-after">@lang('auth.password.old_password')</label>

                            <div class="col-md-6">
                                <input id="old_password" type="password" class="form-control js-password-input" name="old_password">

                                @if ($errors->has('old_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('old_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                            <label for="new_password" class="col-md-4 control-label colon-after">@lang('auth.password.new_password')</label>

                            <div class="col-md-6">
                                <input id="new_password" type="password" class="form-control js-password-input" name="new_password">

                                @if ($errors->has('new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                            <label for="new_password_confirmation" class="col-md-4 control-label colon-after">@lang('auth.password.confirm_new_password')</label>
                            <div class="col-md-6">
                                <input id="new_password_confirmation" type="password" class="form-control js-password-input" name="new_password_confirmation">
                                <div class="toggle-password-display-container js-toggle-password-display-container">
                                    <a class="js-toggle-password-display" data-password-display="show"><i class="fa fa-eye"></i> @lang('auth.login.show_passwords')</a>
                                    <a class="js-toggle-password-display" data-password-display="hide" style="display:none;"><i class="fa fa-eye-slash"></i> @lang('auth.login.hide_passwords')</a>
                                </div>

                                @if ($errors->has('new_password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary single-click">
                                    <i class="fa fa-btn fa-refresh"></i> @lang('auth.password.change_password')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
