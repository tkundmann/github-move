@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $page->name }}
                    </div>
                    <div class="panel-body">

                        {!! nl2br($page->text) !!}

                        <hr />

                        @if ($files->count() > 0)
                            <table id="fileTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>@sortablelink('filename', Lang::get('page.file'), 'fa fa-sort-alpha', $order)</th>
                                        <th>@sortablelink('name', Lang::get('page.name'), 'fa fa-sort-alpha', $order)</th>
                                        <th>@sortablelink('size', Lang::get('page.size'), 'fa fa-sort-numeric', $order)</th>
                                        @if ($hasFilesWithDate)
                                        <th>@sortablelink('fileDate', Lang::get('page.date'), 'fa fa-sort-numeric', $order)</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                            @foreach ($files as $file)
                                @if (!$page->lotNumberRestricted || ($page->lotNumberRestricted && $fileAccess[$file->id]))
                                <tr>
                                    <td>
                                        <i class="fa fa-file-o margin-right-sm"></i>
                                        @if ($fileAvailability[$file->id])
                                            <a href="{{ $file->url . '?v=' . $file->updatedAt->format('Ymdhis')}}" target="_blank">{{ str_limit($file->filename, $limit) }}</a>
                                        @else
                                            <i class="fa fa-exclamation-triangle margin-right-sm" title="@lang('page.file_not_found')"></i>
                                            <span>{{ str_limit($file->filename, $limit) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span>{{ $file->name ? str_limit($file->name, $limit) : '-' }}</span>
                                    </td>
                                    <td>
                                        <span>{{ StringHelper::formatFileSize($file->size) }}</span>
                                    </td>
                                    @if ($hasFilesWithDate)
                                    <td>
                                        <span>{{ $file->fileDate ? $file->fileDate->format('m/d/Y') : '-'}}</span>
                                    </td>
                                    @endif
                                </tr>
                                @endif
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
            $('#fileTable').stickyTableHeaders();
        });
    </script>
@endsection
