@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.file.create.upload_file')
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        {{ Form::open(['route' => ['admin.file.create'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true, 'id' => 'file_create_form']) }}
                        {{ csrf_field() }}

                        <div class="form-group @if($errors->has('site')) has-error @endif">
                            {{ Form::label('site', trans('admin.file.create.site'), ['class' => 'col-sm-3 control-label colon-after']) }}
                            <div class="col-sm-6">
                                {{ Form::select('site', $sites, Input::get('site') ? Input::get('site') : old('site'), ['class' => 'selectpicker form-control', 'title' => trans('common.select')]) }}
                            </div>
                            <div class="col-sm-3">
                                <div class="alert alert-field alert-info text-center">
                                    @lang('admin.file.create.site_reload_warning')
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{----}}@if($errors->has('type')) has-error @endif">
                            {{ Form::label('type', trans('admin.file.create.type'), ['class' => 'col-sm-3 control-label colon-after']) }}
                            <div class="col-sm-6">
                                @if (!Input::get('site') && !old('site'))
                                    {{ Form::select('type', $types, Input::get('type') ? Input::get('type') : old('type'), ['class' => 'selectpicker form-control', 'title' => trans('common.select'), 'disabled' => 'disabled' ]) }}
                                @else
                                    {{ Form::select('type', $types, Input::get('type') ? Input::get('type') : old('type'), ['class' => 'selectpicker form-control', 'title' => trans('common.select')]) }}
                                @endif
                                @if ($errors->has('type'))
                                    {!! $errors->first('type', '<small class="text-danger">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{----}}@if($errors->has('file')) has-error @endif">
                            {{ Form::label('file', trans('admin.file.create.file'), ['class' => 'col-sm-3 control-label colon-after']) }}
                            <div class="col-sm-6">
                                <div class="fileinput fileinput-new input-group margin-bottom-none" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">@lang('common.file.select_file')</span>
                                        <span class="fileinput-exists">@lang('common.file.change')</span>
                                        <input type="file" name="file">
                                    </span>
                                    <span class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">@lang('common.file.remove')</span>
                                </div>
                                @if ($errors->has('file'))
                                    {!! $errors->first('file', '<small class="text-danger">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <hr/>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success single-click"><i
                                        class="fa fa-btn fa-upload"></i>@lang('common.upload')</button>
                        </div>

                        {{ Form::close() }}
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
            $('#file_create_form').submit();
        });

        $('.fileinput').on("change.bs.fileinput", function() {
            var value = $('input[name="file"]').val();
            if (value) {
                if (value.length > {{ $limit }}) {
                    $('.fileinput-filename').text(value.substr(0, {{ $limit }}) + '...');
                }
                else {
                    $('.fileinput-filename').text(value);
                }
            }
        });
    });
</script>
@endsection