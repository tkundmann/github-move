@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.page.create.create_page')
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div>
                            {{ Form::open(['route' => 'admin.page.create', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'page_create_form']) }}
                            {{ csrf_field() }}

                            <div class="form-group @if($errors->has('site')) has-error @endif">
                                {{ Form::label('site', trans('admin.page.page.site'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('site', $sites, Input::get('site') ? Input::get('site') : old('site'), ['class' => 'selectpicker form-control', 'title' => trans('common.select')]) }}
                                </div>
                                <div class="col-sm-3">
                                    <div class="alert alert-field alert-info text-center">
                                        @lang('admin.page.create.site_reload_warning')
                                    </div>
                                </div>
                            </div>

                            <div class="form-group @if($errors->has('type')) has-error @endif">
                                {{ Form::label('type', trans('admin.page.page.type'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    @if (!Input::get('site') && !old('site'))
                                    {{ Form::select('type', $types, null, ['class' => 'selectpicker form-control', 'title' => trans('common.select'), 'disabled' => 'disabled' ]) }}
                                    @else
                                    {{ Form::select('type', $types, null, ['class' => 'selectpicker form-control', 'title' => trans('common.select')]) }}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{----}}@if($errors->has('name')) has-error @endif">
                                {{ Form::label('name', trans('admin.page.page.name'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('name', Input::get('name') ? Input::get('name') : old('name'), ['class' => 'form-control']) }}
                                    @if ($errors->has('name'))
                                        {!! $errors->first('name', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div id="codeGroup" class="form-group{{----}}@if($errors->has('code')) has-error @endif">
                                {{ Form::label('code', trans('admin.page.page.code'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('code', Input::get('code') ? Input::get('code') : old('code'), ['class' => 'form-control']) }}
                                    @if ($errors->has('code'))
                                        {!! $errors->first('code', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{----}}@if($errors->has('description')) has-error @endif">
                                {{ Form::label('description', trans('admin.page.page.description'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('description', Input::get('description') ? Input::get('description') : old('description'), ['class' => 'form-control']) }}
                                    @if ($errors->has('description'))
                                        {!! $errors->first('description', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div id="textGroup" class="form-group{{----}}@if($errors->has('text')) has-error @endif">
                                {{ Form::label('text', trans('admin.page.page.text'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::textarea('text', Input::get('text') ? Input::get('text') : old('text'), ['class' => 'form-control']) }}
                                    @if ($errors->has('text'))
                                        {!! $errors->first('text', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>

                            <div id="userRestrictedGroup" class="form-group @if($errors->has('user_restricted')) has-error @endif">
                                {{ Form::label('user_restricted', trans('admin.page.page.user_restricted'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    <label class="radio-inline">
                                        {{ Form::radio('user_restricted', 1, Input::get('user_restricted') !== null ? Input::get('user_restricted') : (old('user_restricted') !== null ? old('user_restricted') : false))  }}
                                        @lang('common.true')
                                    </label>
                                    <label class="radio-inline">
                                        {{ Form::radio('user_restricted', 0, Input::get('user_restricted') !== null ? !Input::get('user_restricted') : (old('user_restricted') !== null ? !old('user_restricted') : true))  }}
                                        @lang('common.false')
                                    </label>
                                </div>
                            </div>

                            <div id="lotNumberRestrictedGroup" class="form-group @if($errors->has('lot_number_restricted')) has-error @endif">
                                {{ Form::label('user_restricted', trans('admin.page.page.lot_number_restricted'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    <label class="radio-inline">
                                        {{ Form::radio('lot_number_restricted', 1, Input::get('lot_number_restricted') !== null ? Input::get('lot_number_restricted') : (old('lot_number_restricted') !== null ? old('lot_number_restricted') : false))  }}
                                        @lang('common.true')
                                    </label>
                                    <label class="radio-inline">
                                        {{ Form::radio('lot_number_restricted', 0, Input::get('lot_number_restricted') !== null ? !Input::get('lot_number_restricted') : (old('lot_number_restricted') !== null ? !old('lot_number_restricted') : true))  }}
                                        @lang('common.false')
                                    </label>
                                </div>
                            </div>

                            <hr/>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success single-click"><i
                                            class="fa fa-btn fa-plus"></i>@lang('common.create')</button>
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

            $('#site').on('change', function (event) {
                $('#site').append('<input type="hidden" name="site_change" value="1"/>');
                $('#page_create_form').submit();
            });

            var typeSelect = $('select[name="type"]');

            var codeInput = $('input[name="code"]');
            var nameInput = $('input[name="name"]');
            var textInput = $('textarea[name="text"]');
            var siteSelect = $('select[name="site"]');
            var userRestrictedInput = $('input[name="user_restricted"]');
            var lotNumberRestrictedInput = $('input[name="lot_number_restricted"]');

            var typeSelectValueChanged = false;

            typeSelect.on('change', function(event) {
                var value = $(this).val();
                if (value == 'Standard') {
                    if ((nameInput.val() == 'Certificates of Data Wipe') || (nameInput.val() == 'Certificates of Recycling') || (nameInput.val() == 'Settlements')) {
                        nameInput.val(value);
                    }
                }
                else {
                    nameInput.val(value);
                }


                typeSelectValueChanged = true;
                determineVisibility(value);
            });

            function determineVisibility(value) {
                var siteGroup = $('#siteGroup');

                var codeGroup = $('#codeGroup');
                var textGroup = $('#textGroup');
                var userRestrictedGroup = $('#userRestrictedGroup');
                var lotNumberRestrictedGroup = $('#lotNumberRestrictedGroup');

                var canChangeVisibility = !siteGroup.hasClass('has-error') || (siteGroup.hasClass('has-error') && typeSelectValueChanged);
                if (canChangeVisibility) {
                    if (value == 'Standard') {
                        var siteSelectValue = siteSelect.selectpicker('val');

                        if (siteSelectValue) {
                            if (codeGroup.length > 0) {
                                codeGroup.show();
                            }
                            if (textGroup.length > 0) {
                                textGroup.show();
                            }
                            if (userRestrictedGroup.length > 0) {
                                userRestrictedGroup.show();
                            }
                            if (lotNumberRestrictedGroup.length > 0) {
                                lotNumberRestrictedGroup.show();
                            }
                        }
                    }
                    else {
                        if (codeGroup.length > 0) {
                            codeGroup.hide();
                            codeInput.val(null);
                        }
                        if (textGroup.length > 0) {
                            textGroup.hide();
                            textInput.val(null);
                        }
                        if (userRestrictedGroup.length > 0) {
                            userRestrictedGroup.hide();
                            userRestrictedInput.prop('checked', false);
                            $('input[name="user_restricted"][value="0"]').prop('checked', true);
                        }
                        if (lotNumberRestrictedGroup.length > 0) {
                            lotNumberRestrictedGroup.hide();
                            lotNumberRestrictedInput.prop('checked', false);
                            $('input[name="lot_number_restricted"][value="0"]').prop('checked', true);
                        }
                    }
                }
            }

            typeSelect.on('loaded.bs.select', function (event) {
                determineVisibility(typeSelect.val());
            });
        });
    </script>
@endsection
