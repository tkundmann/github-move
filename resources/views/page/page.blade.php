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

                        @if ($page->files->count() > 0)
                            <table id="fileTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('page.file')</th>
                                        <th>@lang('page.name')</th>
                                        <th>@lang('page.size')</th>
                                        @if ($hasFilesWithDate)
                                        <th>@lang('page.date')</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                            @foreach ($page->files as $file)
                                @if (!$page->lotNumberRestricted || ($page->lotNumberRestricted && $fileAccess[$file->id]))
                                <tr>
                                    <td>
                                        <i class="fa fa-file-o margin-right-sm"></i>
                                        @if ($fileAvailability[$file->id])
                                            <a href="{{ $file->url }}" target="_blank">{{ $file->filename }}</a>
                                        @else
                                            <i class="fa fa-exclamation-triangle margin-right-sm" title="@lang('page.file_not_found')"></i>
                                            <span>{{ $file->filename }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span>{{ $file->name ? $file->name : '-' }}</span>
                                    </td>
                                    <td>
                                        <span>{{ StringHelper::formatFileSize($file->size) }}</span>
                                    </td>
                                    @if ($hasFilesWithDate)
                                    <td>
                                        <span>{{ $file->fileDate ? $file->fileDate->format('m/Y') : '-'}}</span>
                                    </td>
                                    @endif
                                </tr>
                                @endif
                            @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info fade">
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
