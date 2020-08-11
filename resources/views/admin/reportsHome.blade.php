@extends('layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                @if (!$site && ContextHelper::isAdminContext($context))
                    <div class="panel-heading">@lang('admin.reports.home.title')</div>
                @endif

                <div class="panel-body">
                    <p>@lang('admin.reports.home.message')</p>

                    <div class="panel panel-default report-listing-container">
                        <div class="panel-heading">@lang('admin.reports.certificates.title')</div>
                        <div class="panel-body">
                            <p>@lang('admin.reports.certificates.description')</p>
                            <p><a href="/admin/reports/certificates">@lang('admin.reports.go_to_reporting_tool')</a></p>
                        </div>
                    </div>

                    <div class="panel panel-default report-listing-container">
                        <div class="panel-heading">@lang('admin.reports.pickuprequests.title')</div>
                        <div class="panel-body">
                            <p>@lang('admin.reports.pickuprequests.description')</p>
                            <p><a href="/admin/reports/pickuprequests">@lang('admin.reports.go_to_exporting_tool')</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
