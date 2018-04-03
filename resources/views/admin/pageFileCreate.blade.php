@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.page.file.create.upload_file_to_page', ['page' => $page->name])
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        {{ Form::open(['route' => ['admin.page.file.create', $page->id], 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) }}
                        {{ csrf_field() }}

                        <div class="form-group{{----}}@if($errors->has('file')) has-error @endif">
                            {{ Form::label('file', trans('admin.page.file.create.file'), ['class' => 'col-sm-4 control-label colon-after']) }}
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

                        <div class="form-group{{----}}@if($errors->has('name')) has-error @endif">
                            {{ Form::label('name', trans('admin.page.file.create.file_name'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                {{ Form::text('name', old('name'), ['class' => 'form-control']) }}
                                @if ($errors->has('name'))
                                    {!! $errors->first('name', '<small class="text-danger">:message</small>') !!}
                                @endif
                            </div>
                        </div>

                        @if ($page->type == 'Standard')
                        <div class="form-group{{----}}@if($errors->has('file_date')) has-error @endif">
                            {{ Form::label('file_date', trans('admin.page.file.create.file_date'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                <input id="file_date" name="file_date" data-provide="datepicker" data-date-clear-btn="true" data-date-min-view-mode="months" data-date-autoclose="true" data-date-format="mm/dd/yyyy"
                                       type="text" class="form-control" placeholder="@lang('common.select')" value="{{ old('file_date') }}"/>
                                @if ($errors->has('file_date'))
                                    {!! $errors->first('file_date', '<small class="text-danger">:message</small>') !!}
                                @endif
                            </div>
                            <div class="col-sm-2">
                                <div class="alert alert-field alert-info text-center">
                                    @lang('admin.page.file.create.file_date_optional')
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (($page->type == 'Standard') && $lotNumbers && count($lotNumbers) > 0)
                        <div class="form-group @if($errors->has('lot_numbers')) has-error @endif">
                            {{ Form::label('lot_numbers', trans('admin.page.file.create.lot_numbers'), ['class' => 'col-sm-4 control-label colon-after']) }}
                            <div class="col-sm-6">
                                {{ Form::select('lot_numbers[]', $lotNumbers, Input::get('lot_numbers') ? Input::get('lot_numbers') : old('lot_numbers'), ['class' => 'selectpicker form-control', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'multiple' => 'true']) }}
                            </div>
                            <div class="col-sm-2">
                                <div class="alert alert-field alert-info text-center">
                                    @lang('admin.page.file.create.lot_numbers_optional')
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true))
                            <div class="form-group{{----}}@if($errors->has('shipment')) has-error @endif">
                                {{ Form::label('shipment', trans('admin.page.file.create.shipment'), ['class' => 'col-sm-4 control-label colon-after']) }}
                                <div class="col-sm-6">
                                    {{ Form::text('shipment', old('shipment'), ['class' => 'form-control']) }}
                                    @if ($errors->has('shipment'))
                                        {!! $errors->first('shipment', '<small class="text-danger">:message</small>') !!}
                                    @endif
                                </div>
                            </div>
                        @endif

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
        $('.fileinput').on("change.bs.fileinput", function() {
            var value = $('input[name="file"]').val();
            var filePathPieces = value.split("\\");
            var fileName = filePathPieces[filePathPieces.length-1];
            if (fileName) {
                if (fileName.length > {{ $limit }}) {
                    $('.fileinput-filename').text(fileName.substr(0, {{ $limit }}) + '...');
                }
                else {
                    $('.fileinput-filename').text(fileName);
                }
                $('input[name="name"]').val(fileName);
            }
        });
        $('.fileinput').on("change.bs.fileinput clear.bs.fileinput reset.bs.fileinput", function() {
            var value = $('input[name="file"]').val();
            var filePathPieces = value.split("\\");
            var fileName = filePathPieces[filePathPieces.length-1];
            $('input[name="name"]').val(fileName);
        });
    });
</script>
@endsection

