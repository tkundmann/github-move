@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('main.layout.menu.search_shipments')</div>
                    <div class="panel-body">
                        <p>
                            @lang('shipment.search.use_1')
                            <br>
                            @lang('shipment.search.use_2')
                        </p>
                        <p>@lang('shipment.search.use_csv')</p>

                        <hr>

                        <form id="form" class="form-horizontal">
                            <div class="text-center">
                                <button id="form_search_button" type="submit"
                                        formaction="{{ route('shipment.search.result') }}"
                                        formmethod="GET" class="btn btn-primary single-click">
                                    <i class="fa fa-btn fa-search"></i> @lang('shipment.search.search_shipments')
                                </button>
                                <button id="form_export_button" type="submit"
                                        formaction="{{ route('shipment.search.export') }}"
                                        formmethod="GET" class="btn btn-primary single-click">
                                    <i class="fa fa-btn fa-table"></i> @lang('shipment.search.export_shipments')
                                </button>
                            </div>

                            <hr>

                            <div class="container-fluid">
                                <div class="row">
                                    <?php $i = 0 ?>
                                    @foreach($simpleFields as $field => $label)
                                        @if ($i == 0)
                                            <div class="col-md-5">
                                        @endif
                                        @if ($i == ceil(count($simpleFields)/2))
                                            </div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-5">
                                        @endif
                                            @if(in_array($field, $fieldCategories['exact'], true))
                                                <div class="form-group">
                                                    <label for="{{ $field }}" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                    {{ Form::select($field, array_combine(${$field . '_values'}, ${$field . '_values'}), old($field), ['class' => 'selectpicker form-control', 'title' => trans('common.select'), 'data-live-search' => 'true', 'data-actions-box' => 'true']) }}
                                                </div>
                                            @endif

                                            @if(in_array($field, $fieldCategories['string_like'], true))
                                                <div class="form-group">
                                                    <label for="{{ $field }}" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                    <input id="{{ $field }}" type="text" class="form-control" name="{{ $field }}" value="{{ old($field) }}">
                                                </div>
                                            @endif

                                            @if(in_array($field, $fieldCategories['string_multi'], true))
                                                <div class="form-group">
                                                    <label class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                    <div class="input-group">
                                                        {{ Form::select($field . '_select',
                                                        ['equals' => Lang::get('common.equals'),
                                                        'begins_with' => Lang::get('common.begins_with'),
                                                        'contains' => Lang::get('common.contains'),
                                                        'ends_in' => Lang::get('common.ends_in')],
                                                        old($field . '_select'), ['class' => 'selectpicker form-control no-float', 'data-width' => 'auto']) }}
                                                        <span class="input-group-addon">-</span>
                                                        <input id="{{ $field }}" name="{{ $field }}" type="text" size="19" class="form-control" value="{{ old($field) }}">
                                                    </div>
                                                </div>
                                            @endif

                                            @if(in_array($field, $fieldCategories['date_from_to'], true))
                                                <div class="form-group">
                                                    <label class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}
                                                        <small>({{ Constants::DATE_FORMAT_LABEL }})</small>
                                                    </label>
                                                    <div class="input-group">
                                                        <input id="{{ $field }}_from" name="{{ $field }}_from" data-provide="datepicker" data-date-clear-btn="true" data-date-today-btn="linked" data-date-autoclose="true" data-date-format="{{ Constants::DATE_FORMAT_JS }}"
                                                               type="text" class="form-control" placeholder="@lang('common.from')" value="{{ old($field . '_from') }}"/>
                                                        <span class="input-group-addon">-</span>
                                                        <input id="{{ $field }}_to" name="{{ $field }}_to" data-provide="datepicker" data-date-clear-btn="true" data-date-today-btn="linked" data-date-autoclose="true" data-date-format="{{ Constants::DATE_FORMAT_JS }}"
                                                               type="text" class="form-control" placeholder="@lang('common.to')" value="{{ old($field . '_to') }}"/>
                                                </div>
                                                </div>
                                            @endif

                                            @if(in_array($field, $fieldCategories['int_less_greater'], true))
                                                <div class="form-group">
                                                    <label for="{{ $field }}" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                    <div class="input-group">
                                                        <input id="{{ $field }}_greater_than" name="{{ $field }}_greater_than" type="number" class="form-control"
                                                               placeholder="@lang('common.greater_than')" value="{{ old($field . '_greater_than') }}"/>
                                                        <span class="input-group-addon">-</span>
                                                        <input id="{{ $field }}_less_than" name="{{ $field }}_less_than" type="number" class="form-control"
                                                               placeholder="@lang('common.less_than')" value="{{ old($field . '_less_than') }}"/>
                                                </div>
                                                </div>
                                            @endif

                                            @if(in_array($field, $fieldCategories['float_less_greater'], true))
                                                <div class="form-group">
                                                    <label for="{{ $field }}" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                    <div class="input-group">
                                                        <input id="{{ $field }}_greater_than" name="{{ $field }}_greater_than" type="number" class="form-control"
                                                               placeholder="@lang('common.greater_than')" step="0.01" value="{{ old($field . '_greater_than') }}"/>
                                                        <span class="input-group-addon">-</span>
                                                        <input id="{{ $field }}_less_than" name="{{ $field }}_less_than" type="number" class="form-control"
                                                               placeholder="@lang('common.less_than')" step="0.01" value="{{ old($field . '_less_than') }}"/>
                                                </div>
                                                </div>
                                            @endif

                                            @if(in_array($field, $fieldCategories['custom'], true))
                                                @if($field === 'vendor_client')
                                                <div class="form-group">
                                                    <label for="vendor_client" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                    {{ Form::select($field, ['all' => Lang::get('common.all')] + $vendorClients, old($field), ['class' => 'selectpicker form-control']) }}
                                                </div>
                                            @endif
                                        @endif
                                        @if ($i == count($simpleFields) - 1)
                                            </div>
                                        @endif
                                        <?php $i++ ?>
                                    @endforeach
                                </div>

                            @if (count($advancedFields) > 0)
                                <hr />

                                <div class="text-center" id="advancedSearchButtonContainer">
                                    <button id="advancedSearchButton" type="button" class="btn btn-default"><i class="fa fa-btn fa-chevron-circle-down"></i>@lang('shipment.search.advanced_search')</button>
                                </div>

                                <div id="advancedSearchContainer" class="row">
                                    <?php $j = 0 ?>
                                    @foreach($advancedFields as $field => $label)
                                        @if ($j == 0)
                                            <div class="col-md-5">
                                                @endif
                                                @if ($j == ceil(count($advancedFields)/2))
                                            </div>
                                            <div class="col-md-1"></div>
                                            <div class="col-md-5">
                                                @endif
                                                @if(in_array($field, $fieldCategories['exact'], true))
                                                    <div class="form-group">
                                                        <label for="{{ $field }}" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                        {{ Form::select($field, array_combine(${$field . '_values'}, ${$field . '_values'}), old($field), ['class' => 'selectpicker form-control', 'title' => trans('common.select'), 'data-live-search' => 'true', 'data-actions-box' => 'true']) }}
                                                    </div>
                                                @endif

                                                @if(in_array($field, $fieldCategories['string_like'], true))
                                                    <div class="form-group">
                                                        <label for="{{ $field }}" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                        <input id="{{ $field }}" type="text" class="form-control" name="{{ $field }}" value="{{ old($field) }}">
                                                    </div>
                                                @endif

                                                @if(in_array($field, $fieldCategories['string_multi'], true))
                                                    <div class="form-group">
                                                        <label class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                        <div class="input-group">
                                                            {{ Form::select($field . '_select',
                                                            ['equals' => Lang::get('common.equals'),
                                                            'begins_with' => Lang::get('common.begins_with'),
                                                            'contains' => Lang::get('common.contains'),
                                                            'ends_in' => Lang::get('common.ends_in')],
                                                            old($field . '_select'), ['class' => 'selectpicker form-control no-float', 'data-width' => 'auto']) }}
                                                            <span class="input-group-addon">-</span>
                                                            <input id="{{ $field }}" name="{{ $field }}" type="text" size="19" class="form-control" value="{{ old($field) }}">
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(in_array($field, $fieldCategories['date_from_to'], true))
                                                    <div class="form-group">
                                                        <label class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}
                                                            <small>({{ Constants::DATE_FORMAT_LABEL }})</small>
                                                        </label>
                                                        <div class="input-group">
                                                            <input id="{{ $field }}_from" name="{{ $field }}_from" data-provide="datepicker" data-date-clear-btn="true" data-date-today-btn="linked" data-date-autoclose="true" data-date-format="{{ Constants::DATE_FORMAT_JS }}"
                                                                   type="text" class="form-control" placeholder="@lang('common.from')" value="{{ old($field . '_from') }}"/>
                                                            <span class="input-group-addon">-</span>
                                                            <input id="{{ $field }}_to" name="{{ $field }}_to" data-provide="datepicker" data-date-clear-btn="true" data-date-today-btn="linked" data-date-autoclose="true" data-date-format="{{ Constants::DATE_FORMAT_JS }}"
                                                                   type="text" class="form-control" placeholder="@lang('common.to')" value="{{ old($field . '_to') }}"/>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(in_array($field, $fieldCategories['int_less_greater'], true))
                                                    <div class="form-group">
                                                        <label for="{{ $field }}" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                        <div class="input-group">
                                                            <input id="{{ $field }}_greater_than" name="{{ $field }}_greater_than" type="number" class="form-control"
                                                                   placeholder="@lang('common.greater_than')" value="{{ old($field . '_greater_than') }}"/>
                                                            <span class="input-group-addon">-</span>
                                                            <input id="{{ $field }}_less_than" name="{{ $field }}_less_than" type="number" class="form-control"
                                                                   placeholder="@lang('common.less_than')" value="{{ old($field . '_less_than') }}"/>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(in_array($field, $fieldCategories['float_less_greater'], true))
                                                    <div class="form-group">
                                                        <label for="{{ $field }}" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                        <div class="input-group">
                                                            <input id="{{ $field }}_greater_than" name="{{ $field }}_greater_than" type="number" class="form-control"
                                                                   placeholder="@lang('common.greater_than')" step="0.01" value="{{ old($field . '_greater_than') }}"/>
                                                            <span class="input-group-addon">-</span>
                                                            <input id="{{ $field }}_less_than" name="{{ $field }}_less_than" type="number" class="form-control"
                                                                   placeholder="@lang('common.less_than')" step="0.01" value="{{ old($field . '_less_than') }}"/>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(in_array($field, $fieldCategories['custom'], true))
                                                    @if($field === 'vendor_client')
                                                        <div class="form-group">
                                                            <label for="vendor_client" class="control-label colon-after">{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</label>
                                                            {{ Form::select($field, ['all' => Lang::get('common.all')] + $vendorClients, old($field), ['class' => 'selectpicker form-control']) }}
                                                        </div>
                                                    @endif
                                                @endif
                                                @if ($j == count($advancedFields) - 1)
                                            </div>
                                        @endif
                                        <?php $j++ ?>
                                    @endforeach
                                </div>
                            @endif

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
            var startWithAdvancedSearch = Boolean('{{ old('advanced') }}');
            var advancedSearchButtonClicked = false;

            $('#form_search_button').click(function (event) {
                if (advancedSearchButtonClicked) {
                    $('#form').append('<input type="hidden" name="advanced" value="1"/>');
                }

                $('#form').append('<input type="hidden" name="page" value="1"/>');

                return true;
            });

            $('#advancedSearchButton').click(function (event) {
                advancedSearchButtonClicked = true;
                $('#advancedSearchButtonContainer').hide();
                $('#advancedSearchContainer').show();
            });

            if (startWithAdvancedSearch) {
                $('#advancedSearchButton').trigger('click');
            }

            @foreach($fields as $field => $label){{--
            --}}@if(in_array($field, $fieldCategories['date_from_to'], true)){{--
            --}}$('#{{ $field . '_from' }}').datepicker().on('changeDate clearDate', function(event) {
                if (event.date) {
                    $('#{{ $field . '_to' }}').datepicker('setStartDate', event.date);
                }
                else {
                    $('#{{ $field . '_to' }}').datepicker('setStartDate', null);
                }
            });

            $('#{{ $field . '_to' }}').datepicker().on('changeDate clearDate', function(event) {
                if (event.date) {
                    $('#{{ $field . '_from' }}').datepicker('setEndDate', event.date);
                }
                else {
                    $('#{{ $field . '_from' }}').datepicker('setEndDate', null);
                }
            });
            @endif
            @endforeach
    });
        </script>
@endsection
