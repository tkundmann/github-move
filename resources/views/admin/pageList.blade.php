@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('admin.page.list.page_list')
                        @if (Input::get('site'))
                            - {{ $pages ? $pages->total() : 0 }} {{ trans_choice('admin.page.list.page', $pages ? $pages->total() : 0) }} @lang('common.found') @endif
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2">
                                <a href="{{ Input::get('site') ? route('admin.page.create', ['site' => Input::get('site')]) : route('admin.page.create') }}" class="btn btn-success">
                                    <i class="fa fa-btn fa-plus"></i>@lang('common.create')
                                </a>
                            </div>
                            <div class="col-md-8 text-center">
                                <form method="GET" action="{{ route('admin.page.list') }}" class="form-inline">
                                    <div class="form-group margin-right-md">
                                        <label for="site" class="control-label colon-after margin-right-md">@lang('admin.page.list.site')</label>
                                        {{ Form::select('site', $sites, Input::get('site'), ['class' => 'selectpicker form-control', 'title' => trans('common.select'), 'data-width' => 'auto']) }}
                                    </div>

                                    <button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-search"></i>@lang('common.search')</button>
                                </form>
                            </div>
                            <div class="col-md-2"></div>
                        </div>

                        <hr>

                        @if (Session::has('success'))
                            <div class="alert alert-success animate">{{ session('success') }}</div>
                        @endif

                        @if (Session::has('fail'))
                            <div class="alert alert-danger animate">{{ session('fail') }}</div>
                        @endif

                        @if ($pages && $pages->count() > 0)
                            <table id="pageSearchTable" class="table table-striped table-bordered withHover">
                                <thead>
                                <tr>
                                    {{--
                                    <th width="22%">@sortablelink('type', Lang::get('admin.page.page.type'), 'fa fa-sort-alpha', $order)</th>
                                    --}}
                                    <th width="30%">@sortablelink('name', Lang::get('admin.page.page.name'), 'fa fa-sort-alpha', $order)</th>
                                    <th width="30%">@sortablelink('code', Lang::get('admin.page.page.code'), 'fa fa-sort-alpha', $order)</th>
                                    <th width="30%">@sortablelink('text', Lang::get('admin.page.page.description'), 'fa fa-sort-alpha', $order)</th>
                                    <th width="10%">{{ trans('common.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($pages as $page)
                                    <tr>
                                        {{--
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.page.edit', ['id' => $page->id]) }}';">{{ $page->type ?: '-' }}</td>
                                        --}}
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.page.edit', ['id' => $page->id]) }}';">{{ $page->name ?: '-' }}</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.page.edit', ['id' => $page->id]) }}';">{{ $page->code ?: '-' }}</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.page.edit', ['id' => $page->id]) }}';">{{ $page->description ?: '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.page.remove', ['id' => $page->id]) }}" data-page="{{ $page->name }}" class="btn btn-danger btn-xs page-remove">
                                                <i class="fa fa-btn fa-trash"></i>@lang('common.remove')
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="text-center">
                                {{ $pages->appends(\Input::except('page'))->links() }}
                            </div>
                        @else
                            @if (Input::get('site'))
                                <div class="alert alert-info animate">
                                    <strong>@lang('common.nothing_found')</strong>
                                </div>
                            @else
                                <div class="alert alert-info animate">
                                    <strong>@lang('admin.page.list.choose_site')</strong>
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
    $('#pageSearchTable').stickyTableHeaders();

    $(".page-remove").click(function (event) {
        event.preventDefault();

        var url = $(this).attr("href");

        bootbox.dialog({
            message: $(this).attr('data-page'),
            title: "@lang('admin.page.list.confirm_remove')",
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