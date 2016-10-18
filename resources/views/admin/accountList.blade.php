@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('admin.accounts.account_list')
                        - {{ $accounts->total() }} {{ trans_choice('admin.accounts.record', $accounts->total()) }} @lang('common.found')</div>
                    <div class="panel-body">
                        <form method="GET" action="{{ route('admin.account.list') }}" class="form-inline">
                            <div class="row">
                                <div class="col-md-1">
                                    <a href="{{ route('admin.account.create') }}" class="btn btn-success"><i class="fa fa-btn fa-plus"></i>@lang('common.create')</a>
                                </div>
                                <div class="col-md-11 text-center">
                                    <div class="form-group margin-right-md">
                                        <label for="role" class="control-label colon-after margin-right-md">@lang('admin.accounts.user.role')</label>
                                        {{ Form::select('role', $roles, Input::get('role'), ['class' => 'selectpicker form-control' ,'data-width' => 'auto']) }}
                                    </div>
                                    <div class="form-group margin-right-md">
                                        <label for="site" class="control-label colon-after margin-right-md">@lang('admin.accounts.user.site')</label>
                                        {{ Form::select('site', ['all' => Lang::get('common.all')] + $sites, Input::get('site'), ['class' => 'selectpicker form-control' ,'data-width' => 'auto']) }}
                                    </div>
                                    <div class="form-group margin-right-md">
                                        <label for="status" class="control-label colon-after margin-right-md">@lang('admin.accounts.user.status')</label>
                                        {{ Form::select('status', ['all' => Lang::get('common.all'), '1' => Lang::get('common.enabled'), '0' => Lang::get('common.disabled') ], Input::get('status'), ['class' => 'selectpicker form-control' ,'data-width' => 'auto']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row margin-top-lg">
                                <div class="col-md-12 text-center">
                                    <div class="form-group margin-right-md">
                                        <label for="name_email"
                                               class="control-label colon-after margin-right-md">@lang('admin.accounts.user.name_email')</label>
                                        <input id="name_email" type="text" class="form-control" name="name_email"
                                               value="{{ Input::get('name_email') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-btn fa-search"></i>@lang('common.search')</button>
                                </div>
                            </div>
                        </form>

                        <hr>

                        @if (Session::has('success'))
                            <div class="alert alert-success animate">{{ session('success') }}</div>
                        @endif

                        @if (Session::has('fail'))
                            <div class="alert alert-danger animate">{{ session('fail') }}</div>
                        @endif

                        @if ($accounts->count() > 0)
                            <table id="accountSearchTable" class="table table-striped table-bordered withHover">
                                <thead>
                                <tr>
                                    <th>@sortablelink('name', Lang::get('admin.accounts.user.name'), 'fa fa-sort-alpha', $order)</th>
                                    <th>@sortablelink('email', Lang::get('admin.accounts.user.email'), 'fa fa-sort-alpha', $order)</th>
                                    <th>@lang('admin.accounts.user.role')</th>
                                    <th>@sortablelink('site_id', Lang::get('admin.accounts.user.site'), 'fa fa-sort-alpha', $order)</th>
                                    <th>@sortablelink('confirmed', Lang::get('admin.accounts.user.confirmed'), null, $order)</th>
                                    <th>@sortablelink('disabled', Lang::get('admin.accounts.user.enabled'), null, $order)</th>
                                    <th>@lang('common.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($accounts as $account)
                                    <tr>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.account.edit', ['id' => $account->id]) }}';">{{ $account->name ? $account->name : '-' }}</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.account.edit', ['id' => $account->id]) }}';">{{ $account->email ?  $account->email : '-' }}</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.account.edit', ['id' => $account->id]) }}';">
                                            @if ($account->roles->count() > 0)
                                                <?php $accountRoles = $account->roles->pluck('name')->toArray() ?>
                                                @foreach ($accountRoles as $accountRole)
                                                    {{ $accountRole }}@if ($accountRole != last($accountRoles)), @endif
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="pointer"
                                            onclick="window.document.location='{{ route('admin.account.edit', ['id' => $account->id]) }}';">{{ $account->site ?  $account->site->title : '-' }} ({{ $account->site ? $account->site->code : '-' }})</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.account.edit', ['id' => $account->id]) }}';">@if ($account->confirmed) <span class="glyphicon glyphicon-ok"></span> @else <span class="glyphicon glyphicon-remove"></span> @endif </td>
                                        <td class="pointer" onclick="window.document.location='{{ route('admin.account.edit', ['id' => $account->id]) }}';">@if (!$account->disabled) <span class="glyphicon glyphicon-ok"></span> @else <span class="glyphicon glyphicon-remove"></span> @endif </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.account.remove', ['id' => $account->id]) }}"
                                               data-account="{{ $account->name }} / {{ $account->email }}"
                                               class="btn btn-danger btn-xs account-remove">
                                                <i class="fa fa-btn fa-trash"></i>@lang('common.remove')
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="text-center">
                                {{ $accounts->appends(\Input::except('page'))->links() }}
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
            $('#accountSearchTable').stickyTableHeaders();

            $(".account-remove").click(function (event) {
                event.preventDefault();

                var url = $(this).attr("href");

                bootbox.dialog({
                    message: $(this).attr('data-account'),
                    title: "@lang('admin.accounts.edit.confirm_remove')",
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