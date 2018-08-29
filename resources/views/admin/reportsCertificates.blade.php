@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('admin.reports.certificates.title')
                        @if (Input::get('generate-report'))
                            - {{ $certificates ? $certificates->total() : 0 }} {{ trans_choice('admin.reports.certificates.record', $certificates ? $certificates->total() : 0) }} @lang('common.found') @endif
                    </div>
                    <div class="panel-body">
                        <form method="GET" action="{{ route('admin.reports.certificates') }}" class="form-horizontal form-certificates-report">
                        <input type="hidden" name="generate-report" value="yes" />
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="site" class="control-label colon-after">@lang('admin.reports.certificates.report_filter_form.site')</label>
                                        <div class="input-group">
                                            {{ Form::select('site', $sites, Input::get('site'), ['class' => 'selectpicker form-control', 'title' => trans('common.select'), 'data-width' => 'auto']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                        <label class="control-label colon-after">@lang('admin.reports.certificates.report_filter_form.audit_completed_range')
                                            <small>({{ Constants::DATE_FORMAT_LABEL }})</small>
                                        </label>
                                        <div class="input-group">
                                            <input id="audit_completed_from" name="audit_completed_from" data-provide="datepicker"
                                                   type="text" class="form-control" placeholder="@lang('common.from')" value=""/>
                                            <span class="input-group-addon">-</span>
                                            <input id="audit_completed_to" name="audit_completed_to" data-provide="datepicker"
                                                   type="text" class="form-control" placeholder="@lang('common.to')" value=""/>
                                        </div>
                                        <small
                                            @if ($errors->has('audit_completed_from') or $errors->has('audit_completed_to')) class="text-danger" @endif
                                        >@lang('admin.reports.certificates.report_filter_form.complete_date_range_required')</small>
                                    </div>
                                </div>
                            </div>
<!--
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label">@lang('admin.reports.certificates.report_filter_form.has_data_wipe_cert')
                                        </label>
                                        <div class="input-group-inline">
                                            <label class="radio-inline">
                                                {{ Form::radio('has-data-wipe-cert', 0, Input::get('has-data-wipe-cert') !== null ? !Input::get('has-data-wipe-cert') : !old('has-data-wipe-cert')) }}
                                                @lang('common.true')
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('has-data-wipe-cert', 1, Input::get('has-data-wipe-cert') !== null ? Input::get('has-data-wipe-cert') : old('has-data-wipe-cert')) }}
                                                @lang('common.false')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="control-label">@lang('admin.reports.certificates.report_filter_form.has_recycling_cert')
                                        </label>
                                        <div class="input-group-inline">
                                            <label class="radio-inline">
                                                {{ Form::radio('has-recycling-cert', 0, Input::get('has-recycling-cert') !== null ? !Input::get('has-recycling-cert') : !old('has-recycling-cert')) }}
                                                @lang('common.true')
                                            </label>
                                            <label class="radio-inline">
                                                {{ Form::radio('has-recycling-cert', 1, Input::get('has-recycling-cert') !== null ? Input::get('has-recycling-cert') : old('has-recycling-cert')) }}
                                                @lang('common.false')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
-->
                            <div class="row margin-top-md">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-search"></i>@lang('admin.reports.certificates.report_filter_form.generate_report')
                                        </button>
                                        <button id="form_export_button" type="submit"
                                                formaction="{{ route('admin.reports.certificates.export') }}"
                                                formmethod="GET" class="btn btn-primary single-click">
                                            <i class="fa fa-btn fa-table"></i> @lang('admin.reports.certificates.report_filter_form.export_report')
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


                        @if ($certificates && count($certificates) > 0)
                            <div class="text-center">
                                {{ $certificates->appends(\Input::except('page'))->links() }}
                            </div>

                            <table id="reportsCertificatesTable" class="table-certificates-report table table-striped table-bordered withHover">
                                <thead>
                                    <tr>
                                        @foreach ($certReportColumns as $label => $headerConfig)
                                            @if (is_array($headerConfig))
                                                @if ($headerConfig['sortable'])
                                                    <th>@sortablelink($headerConfig['sort_column'], Lang::has('admin.reports.certificates.report_headers.'. $label) ? Lang::trans('admin.reports.certificates.report_headers.' . $label) : $label, 'fa ' .  $headerConfig['sort_fa_icon'], $order)</th>
                                                @endif
                                            @else
                                                <th>@lang('admin.reports.certificates.report_headers.' . $label)</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($certificates as $certificate)
                                    <tr>
                                        <td>{{ $certificate->portalName }}<br/><span class="portal-url">{{'(/' . $certificate->portalURL . ')'}}</span></td>
                                        <td>{{ $certificate->vendorClient }}</td>
                                        <td>{{ $certificate->lotDate }}</td>
                                        <td>{{ $certificate->lotNumber }}</td>
                                        <td>{{ $certificate->auditCompletedDate }}</td>
                                        <td class="{{($certificate->hasCertificateOfDataWipe == 'Yes') ? 'has-cert-file' : 'no-cert-file'}}">
                                            {{ $certificate->hasCertificateOfDataWipe }}
                                            @if ($certificate->hasCertificateOfDataWipe == 'Yes')
                                                &#160;(<a href="{{ $certificate->fileDataWipeURL }}" target="_blank">{{ $certificate->fileDataWipeName }}</a>)
                                            @endif
                                        </td>
                                        <td class="{{($certificate->hasCertificateOfRecycling == 'Yes') ? 'has-cert-file' : 'no-cert-file'}}">
                                            {{ $certificate->hasCertificateOfRecycling }}
                                            @if ($certificate->hasCertificateOfRecycling == 'Yes')
                                                &#160;(<a href="{{ $certificate->fileRecyclingURL }}" target="_blank">{{ $certificate->fileRecyclingName }}</a>)
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="text-center">
                                {{ $certificates->appends(\Input::except('page'))->links() }}
                            </div>

                        @else
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

            $("#audit_completed_from").datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                clearBtn: true,
                todayHighlight: true,
                startDate: '{{ $auditCompletedPickerStartDate }}'
            }).on('changeDate', function (selected) {
                if (selected.date !== undefined) {
                    var minDate = new Date(selected.date.valueOf());
                    $('#audit_completed_to').datepicker('setStartDate', minDate);
                    if ($("#audit_completed_to").val() === '') {
                        $('#audit_completed_to').datepicker('update', $("#audit_completed_from").val());
                    }
                    $(this).datepicker('hide');
                }
            });

            $("#audit_completed_to").datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                clearBtn: true,
                todayHighlight: true,
                startDate: '{{ $auditCompletedPickerStartDate }}'
            }).on('changeDate', function (selected) {
                if (selected.date !== undefined) {
                    var maxDate = new Date(selected.date.valueOf());
                    $('#audit_completed_from').datepicker('setEndDate', maxDate);
                    $(this).datepicker('hide');
                }
            });
    });

    </script>
@endsection