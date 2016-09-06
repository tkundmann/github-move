@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.page.edit.editing_page', ['page' => $page->name])
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                @if (Session::has('success'))
                                    <div class="alert alert-success fade">{{ session('success') }}</div>
                                @endif

                                @if (Session::has('fail'))
                                    <div class="alert alert-danger fade">{{ session('fail') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">@lang('admin.page.edit.details')</div>
                                    <div class="panel-body">
                                        {{ Form::open(['route' => ['admin.page.edit', $page->id], 'method' => 'POST', 'class' => 'form-horizontal']) }}
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            {{ Form::label('site', trans('admin.page.page.site'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ $page->site->title }} ({{ $page->site->code }})</p>
                                            </div>
                                        </div>

                                        {{--
                                        <div class="form-group">
                                            {{ Form::label('type', trans('admin.page.page.type'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ $page->type }}</p>
                                            </div>
                                        </div>
                                        --}}

                                        <div class="form-group{{----}}@if($errors->has('name')) has-error @endif">
                                            {{ Form::label('name', trans('admin.page.page.name'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                            <div class="col-sm-6">
                                                {{ Form::text('name', $page->name, ['class' => 'form-control']) }}
                                                @if ($errors->has('name'))
                                                    {!! $errors->first('name', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        </div>

                                        @if ($page->type == 'Standard')
                                        <div class="form-group">
                                            {{ Form::label('code', trans('admin.page.page.code'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ $page->code }}</p>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="form-group{{----}}@if($errors->has('description')) has-error @endif">
                                            {{ Form::label('description', trans('admin.page.page.description'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                            <div class="col-sm-6">
                                                {{ Form::text('description', $page->description, ['class' => 'form-control']) }}
                                                @if ($errors->has('description'))
                                                    {!! $errors->first('description', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        </div>

                                        @if ($page->type == 'Standard')
                                        <div class="form-group{{----}}@if($errors->has('text')) has-error @endif">
                                            {{ Form::label('text', trans('admin.page.page.text'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                            <div class="col-sm-6">
                                                {{ Form::textarea('text', $page->text, ['class' => 'form-control']) }}
                                                @if ($errors->has('text'))
                                                    {!! $errors->first('text', '<small class="text-danger">:message</small>') !!}
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @if ($page->type == 'Standard')
                                        <div class="form-group @if($errors->has('user_restricted')) has-error @endif">
                                            {{ Form::label('user_restricted', trans('admin.page.page.user_restricted'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                            <div class="col-sm-6">
                                                <label class="radio-inline">
                                                    {{ Form::radio('user_restricted', 1, Input::get('user_restricted') !== null ? Input::get('user_restricted') : (old('user_restricted') !== null ? old('user_restricted') : $page->userRestricted))  }}
                                                    @lang('common.true')
                                                </label>
                                                <label class="radio-inline">
                                                    {{ Form::radio('user_restricted', 0, Input::get('user_restricted') !== null ? !Input::get('user_restricted') : (old('user_restricted') !== null ? !old('user_restricted') : !$page->userRestricted))  }}
                                                    @lang('common.false')
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group @if($errors->has('lot_number_restricted')) has-error @endif">
                                            {{ Form::label('user_restricted', trans('admin.page.page.lot_number_restricted'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                            <div class="col-sm-6">
                                                <label class="radio-inline">
                                                    {{ Form::radio('lot_number_restricted', 1, Input::get('lot_number_restricted') !== null ? Input::get('lot_number_restricted') : (old('lot_number_restricted') !== null ? old('lot_number_restricted') : $page->lotNumberRestricted))  }}
                                                    @lang('common.true')
                                                </label>
                                                <label class="radio-inline">
                                                    {{ Form::radio('lot_number_restricted', 0, Input::get('lot_number_restricted') !== null ? !Input::get('lot_number_restricted') : (old('lot_number_restricted') !== null ? !old('lot_number_restricted') : !$page->lotNumberRestricted))  }}
                                                    @lang('common.false')
                                                </label>
                                            </div>
                                        </div>
                                        @endif

                                        <hr/>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success single-click"><i class="fa fa-btn fa-floppy-o"></i>@lang('common.save')</button>
                                            <a href="{{ route('admin.page.file.list', [ 'id' => $page->id]) }}" class="btn btn-primary margin-left-md"><i class="fa fa-btn fa-file-o"></i>@lang('admin.page.page.view_files')</a>
                                        </div>

                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection