@extends('layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                @if ($site)
                    <div class="panel-heading">{{ $site->title }} @lang('main.home.shipments_assets_access')</div>
                @endif
                @if (!$site && ContextHelper::isAdminContext($context))
                    <div class="panel-heading">@lang('main.home.admin')</div>
                @endif
                <div class="panel-body">
                    <p>@lang('main.home.get_started')</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
