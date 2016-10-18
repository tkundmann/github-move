@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('admin.site.vendor_client.vendor_client_list', ['site' => $currentSite->title])
                        - {{ $vendorClients->total() }} {{ trans_choice('admin.site.vendor_client.record', $vendorClients->total()) }} @lang('common.found')
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1">
                                <a href="{{ route('admin.site.vendorClient.create', ['siteId' => $currentSite->id]) }}" class="btn btn-success"><i class="fa fa-btn fa-share-alt"></i>@lang('admin.site.vendor_client.assign')</a>
                            </div>
                            <div class="col-md-10 text-center">
                                <form method="GET" action="{{ route('admin.site.vendorClient.list', ['siteId' => $currentSite->id]) }}" class="form-inline">
                                    <div class="form-group margin-right-md">
                                        <label for="name"
                                               class="control-label colon-after margin-right-md">@lang('admin.site.vendor_client.vendor_client.name')</label>
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{ Input::get('name') }}">
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

                        @if ($vendorClients->count() > 0)
                            <table id="vendorClientSearchTable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>@sortablelink('name', Lang::get('admin.site.vendor_client.vendor_client.name'), 'fa fa-sort-alpha', $order)</th>
                                    <th>@lang('common.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($vendorClients as $vendorClient)
                                    <tr>
                                        <td>{{ $vendorClient->name ? $vendorClient->name : '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.site.vendorClient.remove', ['siteId' => $currentSite->id, 'vendorClientId' => $vendorClient->id]) }}"
                                               data-vendor-client="{{ $vendorClient->name }}"
                                               class="btn btn-danger btn-xs vendor-client-remove">
                                                <i class="fa fa-btn fa-ban"></i>@lang('admin.site.vendor_client.unassign')
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="text-center">
                                {{ $vendorClients->appends(\Input::except('page'))->links() }}
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
            $('#vendorClientSearchTable').stickyTableHeaders();

            $(".vendor-client-remove").click(function (event) {
                event.preventDefault();

                var url = $(this).attr("href");

                bootbox.dialog({
                    message: $(this).attr('data-vendor-client'),
                    title: "@lang('admin.site.vendor_client.confirm_remove')",
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