@extends('layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('pickup_request.login.login')</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('pickupRequest.login') }}">
                        {{ csrf_field() }}

                        <p class="bold">@lang('pickup_request.login.title', [ 'title' => $data['title']])</p>
                        <p>@lang('pickup_request.login.info')</p>

                        @if ($errors->has('access_denied'))
                            <div class="alert alert-danger fade">
                                <strong>{{ $errors->first('access_denied') }}</strong>
                            </div>
                        @endif

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label colon-after">@lang('pickup_request.login.password')</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group margin-bottom-none">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary single-click">
                                    <i class="fa fa-btn fa-sign-in"></i> @lang('pickup_request.login.login')
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
