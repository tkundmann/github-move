@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('admin.page.file.list.file_list', ['page' => $page->name]) - {{ $files ? $files->total() : 0 }} {{ trans_choice('admin.page.file.list.file', $files ? $files->total() : 0) }} @lang('common.found')
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <a href="{{ route('admin.page.file.create', ['id' => $page->id]) }}" class="btn btn-success">
                            <i class="fa fa-btn fa-upload"></i>@lang('common.upload')
                        </a>

                        <hr />

                        @if (Session::has('success'))
                            <div class="alert alert-success animate">{{ session('success') }}</div>
                        @endif

                        @if (Session::has('fail'))
                            <div class="alert alert-danger animate">{{ session('fail') }}</div>
                        @endif

                        @if ($files && $files->count() > 0)
                        <table class="table table-striped table-bordered withHover">
                            <thead>
                                <tr>
                                    <th width="20%">@sortablelink('name', Lang::get('admin.page.file.edit.name'), 'fa fa-sort-alpha', $order)</th>
                                    <th width="20%">@sortablelink('filename', Lang::get('admin.page.file.edit.filename'), 'fa fa-sort-alpha', $order)</th>
                                    <th width="20%">@sortablelink('size', Lang::get('admin.page.file.edit.size'), 'fa fa-sort-amount', $order)</th>
                                    @if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true))
                                    <th width="20%">@lang('admin.page.file.edit.shipment')</th>
                                    @endif
                                    @if ($page->type == 'Standard')
                                    <th width="20%">@sortablelink('fileDate', Lang::get('admin.page.file.edit.file_date'), 'fa fa-sort-numeric', $order)</th>
                                    @endif
                                    <th width="20%">@lang('common.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($files as $file)
                                <tr>
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->name ? str_limit($file->name, $limit) : '-' }}</td>
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->filename ? str_limit($file->filename, $limit) : '-' }}</td>
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->size ? StringHelper::formatFileSize($file->size) : '-' }}</td>
                                    @if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true))
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->shipment ? $file->shipment->lotNumber : '-' }}</td>
                                    @endif
                                    @if ($page->type == 'Standard')
                                    <td class="pointer" onclick="window.document.location='{{ route('admin.page.file.edit', ['fileId' => $file->id, 'pageId' => $page->id]) }}';">{{ $file->fileDate ? $file->fileDate->format('m/d/Y') : '-' }}</td>
                                    @endif
                                    <td class="text-center">
                                        <a href="{{ route('admin.page.file.remove', ['fileId' => $file->id, 'pageId' => $page->id]) }}"
                                           class="btn btn-danger btn-xs file-remove"
                                           data-file="{{ $file->name }}">
                                            <i class="fa fa-btn fa-trash"></i>@lang('common.remove')
                                        </a>
                                        <a href="{{ $file->url  . '?v=' . $file->updatedAt->format('Ymdhis')}}" target="_blank"
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
                            <div class="alert alert-info animate">
                                <strong>@lang('common.nothing_found')</strong>
                            </div>
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
            title: "@lang('admin.page.file.list.confirm_remove')",
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