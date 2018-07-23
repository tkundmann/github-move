@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('pickup_request.list.title', [ 'title' => $data['title']])
                        - {{ $pickupRequests->total() }} {{ trans_choice('pickup_request.list.record', $pickupRequests->total()) }} @lang('common.found')</div>
                    <div class="panel-body">
                        <form method="POST" action="{{ route('pickupRequest.list', ['token' => Input::get('token')]) }}" class="form-inline">
                        {{ csrf_field() }}
                            <div class="row">

                                <div class="col-md-11 text-center">
                                    <div class="form-group margin-right-md">
                                        <label for="status" class="control-label colon-after margin-right-md">@lang('pickup_request.list.status')</label>
                                        {{ Form::select('status', ['all' => Lang::get('common.all'), '1' => Lang::get('pickup_request.list.editable'), '0' => Lang::get('pickup_request.list.not_editable') ], Input::get('status'), ['class' => 'selectpicker form-control' ,'data-width' => 'auto']) }}
                                    </div>
                                    <div class="form-group margin-right-md">
                                        <label for="name_email"
                                               class="control-label colon-after margin-right-md">@lang('pickup_request.list.pickup_request_id')</label>
                                        <input id="id" type="text" class="form-control" name="id"
                                               value="{{ Input::get('id') }}">
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

                        @if ($pickupRequests->count() > 0)
                            <table id="pickupRequestSearchTable" class="table table-striped table-bordered withHover">
                                <thead>
                                <tr>
                                    <th>@sortablelink('id', Lang::get('pickup_request.list.pickup_request_id'), 'fa fa-sort-alpha', $order)</th>
                                    <th>@lang('pickup_request.company_name')</th>
                                    <th>@lang('pickup_request.contact_name')</th>
                                    <th>@sortablelink('created_at', Lang::get('pickup_request.list.created_at'), null, $order)</th>
                                    <th>@lang('common.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($pickupRequests as $pickupRequest)
                                    <tr>
                                        <td class="pointer" onclick="window.document.location='{{ route('pickupRequest.edit', ['id' => $pickupRequest->id]) }}';">{{ $pickupRequest->id ? $pickupRequest->id : '-' }}</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('pickupRequest.edit', ['id' => $pickupRequest->id]) }}';">{{ $pickupRequest->company_name ?  $pickupRequest->company_name : '-' }}</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('pickupRequest.edit', ['id' => $pickupRequest->id]) }}';">{{ $pickupRequest->contact_name ?  $pickupRequest->contact_name : '-' }}</td>
                                        <td class="pointer" onclick="window.document.location='{{ route('pickupRequest.edit', ['id' => $pickupRequest->id]) }}';">{{ $pickupRequest->created_at ?  $pickupRequest->created_at : '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('pickupRequest.edit', ['id' => $pickupRequest->id]) }}"
                                               data-pickup-request="{{ $pickupRequest->id }} / {{ $pickupRequest->company_name }}"
                                               class="btn btn-danger btn-xs">
                                                <i class="fa fa-btn"></i>@lang('common.edit')
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="text-center">
                                {{ $pickupRequests->appends(\Input::except('page'))->links() }}
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
            $('#pickupRequestSearchTable').stickyTableHeaders();
        });
    </script>
@endsection