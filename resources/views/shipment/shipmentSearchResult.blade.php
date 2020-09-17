@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('shipment.search_result.shipments_search_results') - {{ $shipments->total() }} {{ trans_choice('shipment.search_result.record', $shipments->total()) }} @lang('common.found')</div>
                    <div class="panel-body">
                        <p>@lang('shipment.search_result.listed_below')</p>
                        <p>@lang('shipment.search_result.data_can_be_stored_1')
                            <br>@lang('shipment.search_result.data_can_be_stored_2')</p>

                        <hr>

                        <div class="text-center">
                            <form>
                                {{ csrf_field() }}
                                @foreach (Input::all() as $name => $value)
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                @endforeach

                                <button type="submit" formaction="{{ route('shipment.search.modify') }}" formmethod="POST" class="btn btn-primary"><i class="fa fa-btn fa-pencil-square-o"></i>@lang('shipment.search_result.modify_search')</button>
                                <button type="submit" formaction="{{ route('shipment.search.export') }}" formmethod="GET" class="btn btn-primary single-click"><i class="fa fa-btn fa-table"></i>@lang('shipment.search_result.export_shipments')</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($shipments->count() > 0)
        <div class="tableScrollableContainer">
            <table id="shipmentSearchTable" class="table table-striped table-bordered withHover js-search-results-table">
                <thead>
                <tr>
                    @foreach($fields as $field => $label)
                        @if(in_array($field, array_merge($fieldCategories['int_less_greater'], $fieldCategories['float_less_greater']), true))
                            <th>@sortablelink($field, Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label, 'fa fa-sort-amount', $order)</th>
                        @endif

                        @if(in_array($field, array_merge($fieldCategories['exact'], $fieldCategories['string_like'], $fieldCategories['string_multi'], $fieldCategories['custom']), true))
                            <th>@sortablelink($field, Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label, 'fa fa-sort-alpha', $order)</th>
                        @endif

                        @if(in_array($field, $fieldCategories['date_from_to'], true))
                            <th>@sortablelink($field, Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label, 'fa fa-sort-numeric', $order)</th>
                        @endif
                        @if (in_array($field, $fieldCategories['not_sortable'], true))
                            <th>{{ Lang::has('shipment.'. $label) ? Lang::trans('shipment.' . $label) : $label }}</th>
                        @endif
                        @if (starts_with($field, '!') || starts_with($field, 'hardcoded-'))
                            <th>{{ $label }}</th>
                        @endif
                    @endforeach
                    @if ($site->hasFeature(Feature::HAS_SETTLEMENTS))
                        <th>{{ array_key_exists('settlement', $fields) ? (Lang::has('shipment.'. $fields['settlement']) ? Lang::trans('shipment.' .  $fields['settlement']) :  $fields['settlement']) : Lang::trans('shipment.search_result.settlement') }}</th>
                    @endif
                    <th class="text-center">@lang('shipment.search_result.assets')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($shipments as $shipment)
                    <tr>
                        @foreach($fields as $field => $label)
                            @if (starts_with($field, 'hardcoded-'))
                                <td class="pointer" title="{{ $shipment->$field }}" onclick="window.document.location='{{ route('shipment.details', ['id' => $shipment->id ]) }}';">
                                    {{ str_replace('_', ' ', str_replace('hardcoded-', '', $field)) }}
                                </td>
                            @elseif(in_array($field, array_merge($fieldCategories['exact'], $fieldCategories['string_like'], $fieldCategories['string_multi'], $fieldCategories['custom'], $fieldCategories['int_less_greater'], $fieldCategories['float_less_greater'], $fieldCategories['not_sortable']), true))
                                @if(($field === 'cert_of_data_wipe_num') || ($field === 'cert_of_destruction_num'))
                                    <td title="{{ $shipment->$field }}">
                                @elseif ($field === 'inbound_tracking' || $field === 'outbound_tracking')
                                    <td title="@if(count($shipment->$field) > 1) @lang('shipment.search_result.select_number_to_view_tracking_details') @else @lang('shipment.search_result.click_to_view_tracking_details') @endif">
                                @else
                                    <td class="pointer" onclick="window.document.location='{{ route('shipment.details', ['id' => $shipment->id ]) }}';" title="{{ $shipment->$field }}">
                                @endif
                                        @if (in_array($field, ['freight_charge'], true))
                                            <span @if($shipment->$field < 0)class="text-danger"@endif>{{ $shipment->$field ? Constants::CURRENCY_SYMBOL . $shipment->$field : ' ' }}</span>
                                        @elseif ($field === 'inbound_tracking' || $field === 'outbound_tracking')
                                            @if (isset($shipment->$field))
                                                @if (count($shipment->$field) > 1)
                                                    <select class="selectpicker form-control js-tracking-number-select">
                                                        <option value="">@lang('shipment.search_result.select_number_for_tracking')</option>
                                                        @foreach($shipment->$field as $key => $trackingNumber)
                                                            <option value="https://{{ $trackingNumber[1] }}">{{ $trackingNumber[0] }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    {{ serialize($shipment->$field[0]) }}

                                                @endif
                                            @endif
                                        @elseif ($field === 'cert_of_data_wipe_num')
                                            @if (isset($shipment->certOfDataWipeNum))
                                                @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                    <?php $certOfDataWipePage = $site->pages->where('type', 'Certificates of Data Wipe')->first(); ?>
                                                    <?php $certOfDataWipe = $certOfDataWipePage ? $shipment->files->where('page_id', $certOfDataWipePage->id)->first() : null; ?>
                                                    @if ($certOfDataWipe)
                                                        @if ($site->hasFeature(Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE))
                                                            <a href="{{ $certOfDataWipe->url }}" target="_blank">{{ str_limit($shipment->certOfDataWipeNum, $limit) }} ({{ str_limit($certOfDataWipe->filename, $limit) }})</a>
                                                        @else
                                                            <span>{{ str_limit($shipment->certOfDataWipeNum, $limit) }} ({{ str_limit($certOfDataWipe->filename, $limit) }})</span>
                                                        @endif
                                                    @else
                                                        <span>{{ str_limit($shipment->certOfDataWipeNum, $limit) }}</span>
                                                    @endif
                                                @endif
                                            @else
                                                {{ ' ' }}
                                            @endif
                                        @elseif ($field === 'cert_of_destruction_num')
                                            @if (isset($shipment->certOfDestructionNum))
                                                @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                    <?php $certOfDestructionPage = $site->pages->where('type', 'Certificates of Recycling')->first(); ?>
                                                    <?php $certOfDestruction = $certOfDestructionPage ? $shipment->files->where('page_id', $certOfDestructionPage->id)->first() : null; ?>
                                                    @if ($certOfDestruction)
                                                        @if ($site->hasFeature(Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE))
                                                            <a href="{{ $certOfDestruction->url }}" target="_blank">{{ str_limit($shipment->certOfDestructionNum, $limit) }} ({{ str_limit($certOfDestruction->filename, $limit) }})</a>
                                                        @else
                                                            <span>{{ str_limit($shipment->certOfDestructionNum, $limit) }} ({{ str_limit($certOfDestruction->filename, $limit) }})</span>
                                                        @endif
                                                    @else
                                                        <span>{{ str_limit($shipment->certOfDestructionNum, $limit) }}</span>
                                                    @endif
                                                @endif
                                            @else
                                                {{ ' ' }}
                                            @endif
                                        @else
                                            {{ $shipment->$field ? str_limit($shipment->$field, $limit) : ' ' }}
                                        @endif
                                    </td>
                                @endif
                                @if(in_array($field, $fieldCategories['date_from_to'], true))
                                    <td class="pointer" title="{{ $shipment->$field }}"
                                        onclick="window.document.location='{{ route('shipment.details', ['id' => $shipment->id ]) }}';">{{ isset($shipment->$field) ? $shipment->$field->format(Constants::DATE_FORMAT) : ' ' }}</td>
                                @endif
                                @if (starts_with($field, '!'))
                                    <td class="pointer" title="{{ $shipment->$field }}" onclick="window.document.location='{{ route('shipment.details', ['id' => $shipment->id ]) }}';">
                                        {{ ' ' }}
                                    </td>
                                @endif
                                @endforeach
                                @if ($site->hasFeature(Feature::HAS_SETTLEMENTS))
                                    <?php $settlementPage = $site->pages->where('type', 'Settlements')->first(); ?>
                                    <?php $settlement = $settlementPage ? $shipment->files->where('page_id', $settlementPage->id)->first() : null; ?>
                                    @if ($settlement)
                                        <td>
                                            @if ($site->hasFeature(Feature::SETTLEMENT_AS_FILE))
                                                <a href="{{ $settlement->url }}" target="_blank">{{ str_limit($settlement->filename, $limit) }}</a>
                                            @else
                                                <span>{{ str_limit($settlement->filename, $limit) }}</span>
                                            @endif
                                        </td>
                                    @else
                                        <td>{{ ' ' }}</td>
                                    @endif
                                @endif
                                <td class="text-center text-nowrap">
                                    @if ($shipment->assets_count > 0)
                                    <a href="{{ route('asset.search.result', ['page' => 1, 'lot_number' => $shipment->lot_number, 'lot_number_select' => 'equals']) }}" class="btn btn-primary btn-xs margin-vertical-sm"><i class="fa fa-btn fa-laptop"></i>@lang('shipment.search_result.view_assets') ({{ $shipment->assets_count }})</a>
                                    <a href="{{ route('asset.search.export', ['lot_number' => $shipment->lot_number, 'lot_number_select' => 'equals']) }}" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-table"></i>@lang('shipment.search_result.export_assets') ({{ $shipment->assets_count }})</a>
                                    @else
                                        <span>@lang('shipment.search_result.no_assets_found')</span>
                                    @endif
                                </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="container text-center">
            {{ $shipments->appends(\Input::except('page'))->links() }}
        </div>
    @else
        <div class="alert alert-info animate">
            <strong>@lang('common.nothing_found')</strong>
        </div>
    @endif

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.js-search-results-table').stickyTableHeaders();

            var tableWidth = $('.tableScrollableContainer table').width();
            var bodyWidth = $('body').width();

            if (tableWidth > bodyWidth) {
                manageBigTableSizes();
            }
            else {
               manageSmallTableSizes();
                $(window).resize(manageSmallTableSizes);
            }

            function manageSmallTableSizes() {
                var containerLeft = parseInt($('.container').css('margin-left'));
                var containerPaddingLeft = parseInt($('.container').css('padding-left'));
                var containerWidth = $('.container').width();

                $('.tableScrollableContainer').width(containerWidth);
                $('.tableScrollableContainer').css('margin-left', containerLeft + containerPaddingLeft);
            }

            function manageBigTableSizes() {
                var containerLeft = parseInt($('.container').css('margin-left'));
                var containerPaddingLeft = parseInt($('.container').css('padding-left'));

                $('body').css('min-width', tableWidth + parseInt(containerLeft)*2 + parseInt(containerPaddingLeft)*2);
                $('.container').css('margin-left', containerLeft);
                $('.tableScrollableContainer').css('margin-left', containerLeft + containerPaddingLeft);
                $('.tableScrollableContainer').css('margin-right', containerLeft + containerPaddingLeft);
            }

            $('.js-tracking-number-select').on('change', function (event) {
                var $multiTrackNumSelect = $(this);
                if ($multiTrackNumSelect.val() !== '') {
                    window.open($multiTrackNumSelect.val());
                }
            });
        });
    </script>
@endsection

