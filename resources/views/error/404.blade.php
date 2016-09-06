@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-danger" role="alert">@lang('error.404.not_found')</div>

                <div class="text-center margin-vertical-lg">
                    <button onclick="goBack()" class="btn btn-primary"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                    @if (Auth::check())<a class="btn btn-primary" href="{{ route('logout') }}"><i class="fa fa-btn fa-sign-out"></i>@lang('main.layout.logout')</a>@endif
                </div>
            </div>
        </div>
    </div>
@endsection
