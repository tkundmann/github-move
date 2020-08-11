@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('admin.reports.certificates.title')
                        @if (Input::get('generate-report'))
                            - {{ $certificates ? $certificates->total() : 0 }} {{ trans_choice('admin.reports.certificates.record', $certificates ? $certificates->total() : 0) }} @lang('common.found') @endif

                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        {{ Form::open(['route' => ['admin.reports.certificates'], 'method' => 'POST', 'class' => 'form-horizontal form-certificates-report', 'files' => true, 'id' => 'certificates-report_form']) }}
                        <input type="hidden" name="generate-report" value="yes" />
<!--
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group @if($errors->has('certs_report_file')) has-error @endif">
                                        <label for="certs_report_file" class="control-label colon-after">{{ trans('admin.reports.certificates.report_filter_form.generate_per_certs_report')}}
                                        </label>
                                        <div class="fileinput fileinput-new input-group margin-bottom-none" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">@lang('common.file.select_file')</span>
                                                <span class="fileinput-exists">@lang('common.file.change')</span>
                                                {{ Form::file('certs_report_file') }}
                                            </span>
                                            <span class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">@lang('common.file.remove')</span>
                                        </div>
                                        @if ($errors->has('certs_report_file'))
                                            {!! $errors->first('certs_report_file', '<small class="text-danger">:message</small>') !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="margin-bottom-md" style="text-align: center"><strong>----- OR -----</strong></div>
                                    <div class="formDetails">
                                        <div class="form-group"><strong>Generate per the following filtering criteria:</strong></div>
                                    </div>
                                </div>
                            </div>
 -->
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="site" class="control-label colon-after">@lang('admin.reports.certificates.report_filter_form.site')</label>
                                        <div class="input-group">
                                            {{ Form::select('site', $sites, (isset($paginationParams['site']) ? $paginationParams['site'] : ''), ['class' => 'selectpicker form-control', 'title' => trans('common.select'), 'data-width' => 'auto']) }}
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
                                                   type="text" class="form-control" placeholder="@lang('common.from')" value="{{ (isset($paginationParams['audit_completed_from']) ? $paginationParams['audit_completed_from'] : '') }}"/>
                                            <span class="input-group-addon">-</span>
                                            <input id="audit_completed_to" name="audit_completed_to" data-provide="datepicker"
                                                   type="text" class="form-control" placeholder="@lang('common.to')" value="{{ (isset($paginationParams['audit_completed_to']) ? $paginationParams['audit_completed_to'] : '') }}"/>
                                        </div>
                                        <small
                                            @if ($errors->has('audit_completed_from') or $errors->has('audit_completed_to')) class="text-danger" @endif
                                        >@lang('admin.reports.certificates.report_filter_form.complete_date_range_required')</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row margin-top-md">
                                <div class="col-md-1"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-btn fa-search"></i>@lang('admin.reports.certificates.report_filter_form.display_report_in_browser')
                                        </button>
                                        <button id="form_export_button" type="submit"
                                                formaction="{{ route('admin.reports.certificates.export') }}"
                                                formmethod="POST" class="btn btn-primary single-click">
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


                        @if (isset($certificates) && count($certificates) > 0)
                            <div class="text-center">
                                {{ $certificates->appends($paginationParams)->links() }}
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