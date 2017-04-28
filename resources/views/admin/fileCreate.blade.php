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
                        <div>Use the fields below to upload up to 10 files of the same type to a site per a single form submission.</div>
                    </div>
                    <div class="panel-body">
                        {{ Form::open(['route' => ['admin.file.create'], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true, 'id' => 'file_create_form']) }}
                        {{ csrf_field() }}

                        <div class="form-group @if($errors->has('site')) has-error @endif">
                            {{ Form::label('site', trans('admin.file.create.site'), ['class' => 'col-sm-3 control-label colon-after-required']) }}
                            <div class="col-sm-6">
                                {{ Form::select('site', $sites, Input::get('site') ? Input::get('site') : old('site'), ['class' => 'selectpicker form-control', 'title' => trans('common.select')]) }}
                                @if ($errors->has('site'))
                                    {!! $errors->first('site', '<small class="text-danger">:message</small>') !!}
                                @endif
                            </div>
                            <div class="col-sm-3">
                                <div class="alert alert-field alert-info text-center">
                                    @lang('admin.file.create.site_reload_warning')
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{----}}@if($errors->has('type')) has-error @endif">
                            {{ Form::label('type', trans('admin.file.create.type'), ['class' => 'col-sm-3 control-label colon-after-required']) }}
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

                        @for ($i = 1; $i <= $num_upload_fields; $i++)
                            <div class="form-group{{----}}@if($errors->has('file' . $i)) has-error @endif">
                                <label for="{{ 'file' . $i }}"
                                       class="col-sm-3 control-label colon-after @if($i == 1) colon-after-required @endif">{{ trans('admin.file.create.file') . ' #' . $i }}</label>

                                <div class="col-sm-6">
                                    <div class="fileinput fileinput-new input-group margin-bottom-none" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">@lang('common.file.select_file')</span>
                                            <span class="fileinput-exists">@lang('common.file.change')</span>
                                            {{ Form::file('file' . $i) }}
                                        </span>
                                        <span class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">@lang('common.file.remove')</span>
                                    </div>
                                    @if ($errors->has('file' . $i))
                                        {!! $errors->first('file' . $i, '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>
                        @endfor

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

    });
</script>

@endsection