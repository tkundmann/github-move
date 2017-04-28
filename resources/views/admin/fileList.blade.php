@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.file.list.file_list')
                        @if (Input::get('site'))
                            - {{ $files ? $files->total() : 0 }} {{ trans_choice('admin.file.list.file', $files ? $files->total() : 0) }} @lang('common.found') @endif
                    </div>
                    <div class="panel-body">
                        <form method="GET" action="{{ route('admin.file.list') }}" class="form-inline">
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="{{ Input::get('site') ? route('admin.file.create', ['site' => Input::get('site')]) : route('admin.file.create') }}" class="btn btn-success">
                                        <i class="fa fa-btn fa-plus"></i>@lang('common.upload')
                                    </a>
                                </div>
                                <div class="col-md-8 text-center">
                                    <div class="form-group margin-right-md">
                                        <label for="site" class="control-label colon-after margin-right-md">@lang('admin.file.list.site')</label>
                                        {{ Form::select('site', $sites, Input::get('site'), ['class' => 'selectpicker form-control', 'title' => trans('common.select'), 'data-width' => 'auto']) }}
                                    </div>
                                    <div class="form-group margin-right-md">
                                        <label for="type" class="control-label colon-after margin-right-md">@lang('admin.file.edit.type')</label>
                                        {{ Form::select('type', ['all' => Lang::get('common.all'), 'Certificates of Data Wipe' => 'Certificates of Data Wipe', 'Certificates of Recycling' => 'Certificates of Recycling', 'Settlements' => 'Settlements' ], Input::get('type'), ['class' => 'selectpicker form-control' ,'data-width' => 'auto']) }}
                                    </div>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                            <div class="row margin-top-lg">
                                <div class="col-md-12 text-center">
                                    <div class="form-group margin-right-md">
                                        <label for="filename_name"
                                               class="control-label colon-after margin-right-md">@lang('admin.file.list.filename_name')</label>
                                        <input id="filename_name" type="text" class="form-control" name="filename_name"
                                               value="{{ Input::get('filename_name') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-search"></i>@lang('common.search')</button>
                                </div>
                            </div>
                        </form>

                        <hr />

                        @if (Session::has('success'))
                            <div class="alert alert-success">{!! session('success') !!}</div>
                        @endif

                        @if (Session::has('fail'))
                            <div class="alert alert-danger">{!! session('fail') !!}</div>
                        @endif

                        @if ($files && $files->count() > 0)
                        <table class="table table-striped table-bordered withHover">
                            <thead>
                                <tr>
                                    <th width="16%">@lang('admin.file.edit.type')</th>
                                    <th width="16%">@sortablelink('name', Lang::get('admin.file.edit.name'), 'fa fa-sort-alpha', $order)</th>
                                    <th width="16%">@sortablelink('filename', Lang::get('admin.file.edit.filename'), 'fa fa-sort-alpha', $order)</th>
                                    <th width="16%">@sortablelink('size', Lang::get('admin.file.edit.size'), 'fa fa-sort-amount', $order)</th>
                                    <th width="16%">@lang('admin.file.edit.shipment')</th>
                                    <th width="20%">@lang('common.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($files as $file)
                                <tr>
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.file.edit', ['id' => $file->id]) }}';">{{ $file->page->type }}</td>
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.file.edit', ['id' => $file->id]) }}';">{{ $file->name ? str_limit($file->name, $limit) : '-' }}</td>
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.file.edit', ['id' => $file->id]) }}';">{{ $file->filename ? str_limit($file->filename, $limit) : '-' }}</td>
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.file.edit', ['id' => $file->id]) }}';">{{ $file->size ? StringHelper::formatFileSize($file->size) : '-' }}</td>
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.file.edit', ['id' => $file->id]) }}';">{{ $file->shipment ? $file->shipment->lotNumber : '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.file.remove', ['id' => $file->id]) }}"
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

                        <div class="text-center">
                            {{ $files->appends(\Input::except('page'))->links() }}
                        </div>

                        @else
                            @if (Input::get('site'))
                                <div class="alert alert-info animate">
                                    <strong>@lang('common.nothing_found')</strong>
                                </div>
                            @else
                                <div class="alert alert-info animate">
                                    <strong>@lang('admin.file.list.choose_site')</strong>
                                </div>
                            @endif
                        @endif
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
            title: "@lang('admin.file.list.confirm_remove')",
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