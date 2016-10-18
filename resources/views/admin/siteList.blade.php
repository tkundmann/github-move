@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('admin.site.site_list')
                        - {{ $sites->total() }} {{ trans_choice('admin.site.record', $sites->total()) }} @lang('common.found')</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1">
                                <a href="{{ route('admin.site.create') }}" class="btn btn-success"><i class="fa fa-btn fa-plus"></i>@lang('common.create')</a>
                            </div>
                            <div class="col-md-10 text-center">
                                <form method="GET" action="{{ route('admin.site.list') }}" class="form-inline">
                                    <div class="form-group margin-right-md">
                                        <label for="title"
                                               class="control-label colon-after margin-right-md">@lang('admin.site.site.title')</label>
                                        <input id="title" type="text" class="form-control" name="title"
                                               value="{{ Input::get('title') }}">
                                    </div>
                                    <div class="form-group margin-right-md">
                                        <label for="type" class="control-label colon-after margin-right-md">@lang('admin.site.site.type')</label>
                                        {{ Form::select('type', ['all' => Lang::get('common.all'), 'Insight' => 'Insight', 'SAR' => 'SAR', 'Other' => 'Other' ], Input::get('type'), ['class' => 'selectpicker form-control' ,'data-width' => 'auto']) }}
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-search"></i>@lang('common.search')</button>
                                </form>
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>

                        <hr>

                        @if (Session::has('success'))
                            <div class="alert alert-success animate">{{ session('success') }}</div>
                        @endif

                        @if (Session::has('fail'))
                            <div class="alert alert-danger animate">{{ session('fail') }}</div>
                        @endif

                        @if ($sites->count() > 0)
                            <table id="siteSearchTable" class="table table-striped table-bordered withHover">
                                <thead>
                                <tr>
                                    <th>@sortablelink('title', Lang::get('admin.site.site.title'), 'fa fa-sort-alpha', $order)</th>
                                    <th>@sortablelink('code', Lang::get('admin.site.site.code'), 'fa fa-sort-alpha', $order)</th>
                                    <th>@sortablelink('type', Lang::get('admin.site.site.type'), 'fa fa-sort-alpha', $order)</th>
                                    <th>@lang('common.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($sites as $siteItem)
                                    <tr>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.site.edit', ['id' => $siteItem->id]) }}';">{{ $siteItem->title ? $siteItem->title : '-' }}</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.site.edit', ['id' => $siteItem->id]) }}';">{{ $siteItem->code ? $siteItem->code : '-' }}</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.site.edit', ['id' => $siteItem->id]) }}';">{{ $siteItem->type ? $siteItem->type : '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.site.remove', ['id' => $siteItem->id]) }}"
                                               data-site="{{ $siteItem->title }} ({{ $siteItem->code }})"
                                               class="btn btn-danger btn-xs site-remove">
                                                <i class="fa fa-btn fa-trash"></i>@lang('common.remove')
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="text-center">
                                {{ $sites->appends(\Input::except('page'))->links() }}
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
        $(document).ready(function () {
            $('#siteSearchTable').stickyTableHeaders();

            $(".site-remove").click(function (event) {
                event.preventDefault();

                var url = $(this).attr("href");

                bootbox.dialog({
                    message: $(this).attr('data-site'),
                    title: "@lang('admin.site.edit.confirm_remove')",
                    buttons: {
                        ok: {
                            label: '@lang('common.remove')',
                            className: 'btn-danger',
                            callback: function () {
                                document.location.href = url;
                            }
                        },
                        cancel: {
                            label: '@lang('common.cancel')',
                            className: 'btn-default',
                            callback: function () {
                                // nothing
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection