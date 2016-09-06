@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.page.file.edit.edit_file', ['file' => str_limit($file->filename, $limit)])
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        {{ Form::open(['route' => ['admin.page.file.edit', $file->page->id, $file->id], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) }}
                        {{ csrf_field() }}

                        <div class="form-group{{----}}@if($errors->has('name')) has-error @endif">
                            {{ Form::label('name', trans('admin.page.file.edit.name'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                {{ Form::text('name', $file->name, ['class' => 'form-control']) }}
                                @if ($errors->has('name'))
                                    {!! $errors->first('name', '<small class="text-danger">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('code', trans('admin.page.file.edit.filename'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                <p class="form-control-static">{{ $file->filename ? str_limit($file->filename, $limit) : '-' }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('size', trans('admin.page.file.edit.size'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                <p class="form-control-static">{{ $file->size ? StringHelper::formatFileSize($file->size) : '-' }}</p>
                            </div>
                        </div>

                        @if ($page->type == 'Standard')
                            <div class="form-group{{----}}@if($errors->has('file_date')) has-error @endif">
                                {{ Form::label('file_date', trans('admin.page.file.edit.file_date'), ['class' => 'col-sm-4 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    <input id="file_date" name="file_date" data-provide="datepicker" data-date-clear-btn="true" data-date-min-view-mode="months" data-date-autoclose="true" data-date-format="mm/yyyy"
                                           type="text" class="form-control" placeholder="@lang('common.select')" value="{{ Input::get('vendor_clients') ? Input::get('file_date') : (old('file_date') ? old('file_date') : ($file->fileDate ? $file->fileDate->format('m/Y') : null )) }}"/>
                                    @if ($errors->has('file_date'))
                                        {!! $errors->first('file_date', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                                <div class="col-sm-2">
                                    <div class="alert alert-field alert-info text-center">
                                        @lang('admin.page.file.edit.file_date_optional')
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (($page->type == 'Standard') && $lotNumbers && count($lotNumbers) > 0)
                            <div class="form-group @if($errors->has('lot_numbers')) has-error @endif">
                                {{ Form::label('lot_numbers', trans('admin.page.file.edit.lot_numbers'), ['class' => 'col-sm-4 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::select('lot_numbers[]', $lotNumbers, Input::get('lot_numbers') ? Input::get('lot_numbers') : old('lot_numbers') ? old('lot_numbers') : $file->lotNumbers()->lists('id')->toArray(), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'multiple' => 'true']) }}
                                </div>
                                <div class="col-sm-2">
                                    <div class="alert alert-field alert-info text-center">
                                        @lang('admin.page.file.edit.lot_numbers_optional')
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true))
                            <div class="form-group{{----}}@if($errors->has('shipment')) has-error @endif">
                                {{ Form::label('shipment', trans('admin.page.file.edit.shipment'), ['class' => 'col-sm-4 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('shipment', $file->shipment->lotNumber, ['class' => 'form-control']) }}
                                    @if ($errors->has('shipment'))
                                        {!! $errors->first('shipment', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>
                        @endif

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
