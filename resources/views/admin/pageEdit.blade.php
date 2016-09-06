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

                                        <div class="form-group">
                                            {{ Form::label('type', trans('admin.page.page.type'), ['class' => 'col-sm-3 control-label colon-after']) }}
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ $page->type }}</p>
                                            </div>
                                        </div>

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
                                            <button type="submit" class="btn btn-success single-click"><i
                                                        class="fa fa-btn fa-floppy-o"></i>@lang('common.save')
                                            </button>
                                        </div>

                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        @lang('admin.page.edit.files')
                                    </div>
                                    <div class="panel-body">
                                        <a href="{{ route('admin.page.file.create', ['id' => $page->id]) }}" class="btn btn-success">
                                            <i class="fa fa-btn fa-upload"></i>@lang('common.upload')
                                        </a>

                                        <hr />

                                        <table class="table table-striped table-bordered withHover">
                                            <thead>
                                                <tr>
                                                    <th>@lang('admin.page.file.edit.name')</th>
                                                    <th>@lang('admin.page.file.edit.filename')</th>
                                                    <th>@lang('admin.page.file.edit.size')</th>
                                                    @if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true))
                                                    <th>@lang('admin.page.file.edit.shipment')</th>
                                                    @endif
                                                    @if ($page->type == 'Standard')
                                                    <th>@lang('admin.page.file.edit.file_date')</th>
                                                    @endif
                                                    <th>@lang('common.actions')</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($page->files as $file)
                                                <tr>
                                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->name ? str_limit($file->name, $limit) : '-' }}</td>
                                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->filename ? str_limit($file->filename, $limit) : '-' }}</td>
                                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->size ? StringHelper::formatFileSize($file->size) : '-' }}</td>
                                                    @if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true))
                                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->shipment ? $file->shipment->lotNumber : '-' }}</td>
                                                    @endif
                                                    @if ($page->type == 'Standard')
                                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->fileDate ? $file->fileDate->format('m/Y') : '-' }}</td>
                                                    @endif
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.page.file.remove', ['fileId' => $file->id, 'pageId' => $page->id]) }}"
                                                           class="btn btn-danger btn-xs file-remove"
                                                           data-file="{{ $file->name }}">
                                                            <i class="fa fa-btn fa-trash"></i>@lang('common.remove')
                                                        </a>
                                                        <a href="{{ $file->url }}" target="_blank"
                                                           class="btn btn-primary btn-xs">
                                                            <i class="fa fa-btn fa-download"></i>@lang('common.download')
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
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

@section('js')
<script>
$(document).ready(function() {
    $(".file-remove").click(function (event) {
        event.preventDefault();

        var url = $(this).attr("href");

        bootbox.dialog({
            message: $(this).attr('data-file'),
            title: "@lang('admin.page.edit.confirm_remove')",
            buttons: {
                ok: {
                    label: '@lang('common.remove')',
                    className: 'btn-danger',
                    callback: function() {
                        document.location.href = url;
                    }
                },
                cancel: {
                    label: '@lang('common.cancel')',
                    className: 'btn-default',
                    callback: function() {
                        // nothing
                    }
                }
            }
        });
    });
});
</script>
@endsection