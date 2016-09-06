@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.file.edit.edit_file', ['file' => str_limit($file->filename, $limit)])
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        {{ Form::open(['route' => ['admin.file.edit', $file->id], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) }}
                        {{ csrf_field() }}

                        <div class="form-group">
                            {{ Form::label('site', trans('admin.file.edit.site'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                <p class="form-control-static">{{ $file->page->site ? $file->page->site->title . ' (' . $file->page->site->code . ')' : '-' }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('type', trans('admin.file.edit.type'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                <p class="form-control-static">{{ $file->page->type ? $file->page->type : '-' }}</p>
                            </div>
                        </div>

                        <div class="form-group{{----}}@if($errors->has('name')) has-error @endif">
                            {{ Form::label('name', trans('admin.file.edit.name'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                {{ Form::text('name', $file->name, ['class' => 'form-control']) }}
                                @if ($errors->has('name'))
                                    {!! $errors->first('name', '<small class="text-danger">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('code', trans('admin.file.edit.filename'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                <p class="form-control-static">{{ $file->filename ? str_limit($file->filename, $limit) : '-' }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('code', trans('admin.file.edit.size'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                <p class="form-control-static">{{ $file->size ? StringHelper::formatFileSize($file->size) : '-' }}</p>
                            </div>
                        </div>

                        <div class="form-group{{----}}@if($errors->has('shipment')) has-error @endif">
                            {{ Form::label('shipment', trans('admin.file.edit.shipment'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                {{ Form::text('shipment', $file->shipment->lotNumber, ['class' => 'form-control']) }}
                                @if ($errors->has('shipment'))
                                    {!! $errors->first('shipment', '<small class="text-danger">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <hr/>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success single-click"><i
                                        class="fa fa-btn fa-floppy-o"></i>@lang('common.save')</button>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
