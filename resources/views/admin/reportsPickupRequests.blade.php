@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('admin.reports.pickuprequests.title')
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
<!--                         @if (Input::get('generate-report'))
                            - {{ $pickupRequests ? $pickupRequests->total() : 0 }} {{ trans_choice('admin.reports.pickuprequests.record', $pickupRequests ? $pickupRequests->total() : 0) }} @lang('common.found') @endif -->
                    </div>
                    <div class="panel-body">
                        {{ Form::open(['route' => ['admin.reports.pickuprequests'], 'method' => 'POST', 'class' => 'form-horizontal form-certificates-report', 'files' => true, 'id' => 'certificates-report_form']) }}
                        <input type="hidden" name="generate-report" value="yes" />

                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="site" class="control-label colon-after">@lang('admin.reports.pickuprequests.report_filter_form.pickuprequest_site_portal')</label>
                                        <div class="input-group">
                                            {{ Form::select('site', $pickupRequestSites, (isset($paginationParams['site']) ? $paginationParams['site'] : ''), ['class' => 'selectpicker form-control', 'title' => trans('common.select'), 'data-width' => 'auto']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="control-label colon-after">@lang('admin.reports.pickuprequests.report_filter_form.pickuprequest_submission_date_range')
                                            <small>({{ Constants::DATE_FORMAT_LABEL }})</small>
                                        </label>
                                        <div class="input-group">
                                            <input id="pickuprequest_submission_from" name="pickuprequest_submission_from" data-provide="datepicker"
                                                   type="text" class="form-control" placeholder="@lang('common.from')" value="{{ (isset($paginationParams['pickuprequest_submission_from']) ? $paginationParams['pickuprequest_submission_from'] : '') }}"/>
                                            <span class="input-group-addon">-</span>
                                            <input id="pickuprequest_submission_to" name="pickuprequest_submission_to" data-provide="datepicker"
                                                   type="text" class="form-control" placeholder="@lang('common.to')" value="{{ (isset($paginationParams['pickuprequest_submission_to']) ? $paginationParams['pickuprequest_submission_to'] : '') }}"/>
                                        </div>
                                        <small
                                            @if ($errors->has('pickuprequest_submission_from') or $errors->has('pickuprequest_submission_to')) class="text-danger" @endif
                                        >@lang('admin.reports.pickuprequests.report_filter_form.complete_pickuprequest_submission_date_required')</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top-md">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
<!--
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-search"></i>@lang('admin.reports.pickuprequests.report_filter_form.display_report_in_browser')
                                        </button>
 -->
                                        <button id="form_export_button" type="submit"
                                                formaction="{{ route('admin.reports.pickuprequests.export') }}"
                                                formmethod="POST" class="btn btn-primary single-click">
                                            <i class="fa fa-btn fa-table"></i> @lang('admin.reports.pickuprequests.report_filter_form.export_report')
                                        </button>
                                    </div>
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


                        @if (!isset($pickupRequests) || count($pickupRequests) == 0)
                            @if (Input::get('generate-report'))
                                <div class="alert alert-info animate">
                                    <strong>@lang('common.nothing_found')</strong>
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
        $(document).ready(function () {
            $('#reportsCertificatesTable').stickyTableHeaders();

            $("#pickuprequest_submission_from").datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                clearBtn: true,
                todayHighlight: true,
                startDate: '{{ $pickupRequestSubmissionPickerStartDate }}',
                endDate: '{{ $pickupRequestSubmissionPickerEndDate }}'
            }).on('changeDate', function (selected) {
                if (selected.date !== undefined) {
                    var minDate = new Date(selected.date.valueOf());
                    $('#pickuprequest_submission_to').datepicker('setStartDate', minDate);
                    if ($("#pickuprequest_submission_to").val() === '') {
                        $('#pickuprequest_submission_to').datepicker('update', $("#pickuprequest_submission_from").val());
                    }
                    $(this).datepicker('hide');
                }
            });

            $("#pickuprequest_submission_to").datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                clearBtn: true,
                todayHighlight: true,
                startDate: '{{ $pickupRequestSubmissionPickerStartDate }}',
                endDate: '{{ $pickupRequestSubmissionPickerEndDate }}'
            }).on('changeDate', function (selected) {
                if (selected.date !== undefined) {
                    var maxDate = new Date(selected.date.valueOf());
                    $('#pickuprequest_submission_from').datepicker('setEndDate', maxDate);
                    $(this).datepicker('hide');
                }
            });
    });

    </script>
@endsection