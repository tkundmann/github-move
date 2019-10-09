@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.site.edit.editing_site', ['site' => $currentSite->title])
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div>
                            {{ Form::open(['route' => ['admin.site.edit', $currentSite->id], 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'site_edit_form','files' => true]) }}
                            {{ csrf_field() }}

                            <div class="form-group @if($errors->has('type')) has-error @endif">
                                {{ Form::label('type', trans('admin.site.site.type'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('type', ['Insight' => 'Insight', 'SAR' => 'SAR', 'Other' => 'Other'], Input::get('type') ? Input::get('type') : old('type') ? old('type') : $currentSite->type, ['class' => 'selectpicker form-control', 'title' => trans('common.select')]) }}
                                    @if ($errors->has('type'))
                                        {!! $errors->first('type', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('title')) has-error @endif">
                                {{ Form::label('title', trans('admin.site.site.title'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('title', Input::get('title') ? Input::get('title') : old('title') ? old('title') : $currentSite->title, ['class' => 'form-control']) }}
                                    @if ($errors->has('title'))
                                        {!! $errors->first('title', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('code')) has-error @endif">
                                {{ Form::label('code', trans('admin.site.site.code'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('code', Input::get('code') ? Input::get('code') : old('code') ? old('code') : $currentSite->code, ['class' => 'form-control']) }}
                                    @if ($errors->has('code'))
                                        {!! $errors->first('code', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('logo')) has-error @endif">
                                {{ Form::label('logo', trans('admin.site.site.logo'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div id="currentLogoContainer" class="col-sm-6">
                                    <img src="{{ $currentSite->logoUrl }}" style="height: 70px; padding: 5px; background-color: black" />
                                    <button id="logoChangeButton" type="button" class="margin-left-lg btn btn-primary"><i class="fa fa-btn fa-refresh"></i>@lang('admin.site.edit.change')</button>
                                </div>
                                <div id="newLogoContainer" class="col-sm-6">
                                    <label class="radio-inline">
                                        {{ Form::radio('logo', 'Insight', false, ['title' => 'Insight', 'style' => 'margin-top: 25px'] ) }}
                                        <img src="/img/logo/logo-insight.png" title="Insight" style="height: 70px; padding: 5px; background-color: black" />
                                    </label>
                                    <label class="radio-inline margin-left-lg">
                                        {{ Form::radio('logo', 'Sipi', false, ['title' => 'Sipi', 'style' => 'margin-top: 25px'] ) }}
                                        <img src="/img/logo/logo-sipi.png" title="Sipi" style="height: 70px; padding: 5px; background-color: black" />
                                    </label>
                                    <label class="radio-inline margin-left-lg">
                                        {{ Form::radio('logo', 'custom', false, ['title' => 'Custom', 'style' => 'margin-top: 0px'] ) }}
                                        <div style="position: relative; top: -3px">Custom</div>
                                    </label>
                                    @if ($errors->has('logo'))
                                        <br/>
                                        {!! $errors->first('logo', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div id="customLogo" class="form-group{{----}}@if($errors->has('file')) has-error @endif">
                                {{ Form::label('file', trans('admin.site.create.custom_logo'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    <div class="fileinput fileinput-new input-group margin-bottom-none" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">@lang('common.file.select_file')</span>
                                        <span class="fileinput-exists">@lang('common.file.change')</span>
                                        <input type="file" name="file" accept="image/*">
                                    </span>
                                        <span class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">@lang('common.file.remove')</span>
                                    </div>
                                    @if ($errors->has('file'))
                                        {!! $errors->first('file', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('color')) has-error @endif">
                                {{ Form::label('code', trans('admin.site.site.color'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('color', Input::get('color') ? Input::get('color') : old('color') ? old('color') : $currentSite->color, ['id' => 'color', 'class' => 'form-control']) }}
                                    @if ($errors->has('color'))
                                        {!! $errors->first('color', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('account-vendor-client-restriction-enabled')) has-error @endif">
                                {{ Form::label('account-vendor-client-restriction-enabled', trans('admin.site.site.account_vendor_client_restriction'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    <label class="radio-inline">
                                        {{ Form::radio('account-vendor-client-restriction-enabled', 1, Input::get('account-vendor-client-restriction-enabled') !== null ? !Input::get('account-vendor-client-restriction-enabled') : old('account-vendor-client-restriction-enabled') ? old('account-vendor-client-restriction-enabled') : $currentSite->hasVendorClientCodeAccessFeature) }}
                                        @lang('common.true')
                                    </label>
                                    <label class="radio-inline">
                                        {{ Form::radio('account-vendor-client-restriction-enabled', 0, Input::get('account-vendor-client-restriction-enabled') !== null ? Input::get('account-vendor-client-restriction-enabled') : old('account-vendor-client-restriction') ? old('account-vendor-client-restriction-enabled') : !$currentSite->hasVendorClientCodeAccessFeature) }}
                                        @lang('common.false')
                                    </label>
                                </div>
                            </div>

                            <hr>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success single-click"><i class="fa fa-btn fa-floppy-o"></i>@lang('common.save')</button>
                                <a href="{{ route('admin.site.vendorClient.list', [ 'siteId' => $currentSite->id]) }}" class="btn btn-primary margin-left-md"><i class="fa fa-btn fa-briefcase"></i>@lang('admin.site.edit.view_vendor_clients')</a>
                                <a href="{{ route('admin.site.lotNumber.list', [ 'siteId' => $currentSite->id]) }}" class="btn btn-primary margin-left-md"><i class="fa fa-btn fa-list-ol"></i>@lang('admin.site.edit.view_lot_numbers')</a>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#color').colorpicker({ horizontal: true, align: 'left', format: 'hex'});

            var customLogoContainer = $('#customLogo');
            var logoRadio = $('input[name="logo"]');
            var logoChangeButton = $('#logoChangeButton');
            var currentLogoContainer = $('#currentLogoContainer');
            var newLogoContainer = $('#newLogoContainer');

            customLogoContainer.hide();
            newLogoContainer.hide();

            function determineCustomLogoVisibility(value) {
                if (value == 'custom') {
                    customLogoContainer.show();
                }
                else {
                    customLogoContainer.hide();
                }
            }

            function showNewLogoContainer() {
                currentLogoContainer.hide();
                newLogoContainer.show();
                $('#site_edit_form').append('<input type="hidden" name="logo_change" value="1" />');
            }

            logoRadio.on('change', function(event) {
                var value = $(this).val();
                determineCustomLogoVisibility(value);
            });

            logoChangeButton.on('click', function(event) {
                showNewLogoContainer();
                determineCustomLogoVisibility($('input[name="logo"]:checked').val());
            });

            var logoChange = {{ Input::get('logo_change') ? 'true' : old('logo_change') ? 'true' : 'false' }};
            if (logoChange) {
                showNewLogoContainer();
                determineCustomLogoVisibility($('input[name="logo"]:checked').val());
            }
        });
    </script>
@endsection
