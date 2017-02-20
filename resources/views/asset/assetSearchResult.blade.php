@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('asset.search_result.assets_search_results')
                        - {{ $assets->total() }} {{ trans_choice('asset.search_result.record', 10) }} @lang('common.found')</div>
                    <div class="panel-body">
                        <p>@lang('asset.search_result.listed_below')</p>
                        <p>@lang('asset.search_result.data_can_be_stored_1')
                            <br>@lang('asset.search_result.data_can_be_stored_2')</p>

                        <hr>

                        <div class="text-center">
                            <form>
                                {{ csrf_field() }}
                                @foreach (Input::all() as $name => $value)
                                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                                @endforeach

                                <button type="submit" formaction="{{ route('asset.search.modify') }}" formmethod="POST"
                                        class="btn btn-primary"><i
                                            class="fa fa-btn fa-pencil-square-o"></i>@lang('asset.search_result.modify_search')
                                </button>
                                <button type="submit" formaction="{{ route('asset.search.export') }}" formmethod="GET"
                                        class="btn btn-primary single-click"><i
                                            class="fa fa-btn fa-table"></i>@lang('asset.search_result.export_assets')
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($assets->count() > 0)
    <div class="tableScrollableContainer">
        <table id="assetSearchTable" class="table table-striped table-bordered withHover">
            <thead>
            <tr>
                @foreach($fields as $field => $label)
                    @if(in_array($field, array_merge($fieldCategories['int_less_greater'], $fieldCategories['float_less_greater']), true))
                        <th>@sortablelink('asset.' . $field, Lang::has('asset.'. $label) ? Lang::trans('asset.' . $label) : $label, 'fa fa-sort-amount', $order)</th>
                    @endif
                    @if(in_array($field, array_merge($fieldCategories['exact'], $fieldCategories['string_like'], $fieldCategories['string_multi'], $fieldCategories['custom']), true))
                        <th>@sortablelink('asset.' . $field, Lang::has('asset.'. $label) ? Lang::trans('asset.' . $label) : $label, 'fa fa-sort-alpha', $order)</th>
                    @endif
                    @if(in_array($field, $fieldCategories['date_from_to'], true))
                        <th>@sortablelink('asset.' . $field, Lang::has('asset.'. $label) ? Lang::trans('asset.' . $label) : $label, 'fa fa-sort-numeric', $order)</th>
                    @endif
                    @if(in_array($field, array_merge($fieldCategories['shipment']['int_less_greater'], $fieldCategories['shipment']['float_less_greater']), true))
                        <th>@sortablelink('shipment.' . $field, Lang::has('asset.'. $label) ? Lang::trans('asset.' . $label) : $label, 'fa fa-sort-amount', $order)</th>
                    @endif
                    @if(in_array($field, array_merge($fieldCategories['shipment']['exact'], $fieldCategories['shipment']['string_like'], $fieldCategories['shipment']['string_multi'], $fieldCategories['shipment']['custom']), true))
                        <th>@sortablelink('shipment.' . $field, Lang::has('asset.'. $label) ? Lang::trans('asset.' . $label) : $label, 'fa fa-sort-alpha', $order)</th>
                    @endif
                    @if(in_array($field, $fieldCategories['shipment']['date_from_to'], true))
                        <th>@sortablelink('shipment.' . $field, Lang::has('asset.'. $label) ? Lang::trans('asset.' . $label) : $label, 'fa fa-sort-numeric', $order)</th>
                    @endif
                    @if (starts_with($field, '!') || starts_with($field, 'hardcoded-'))
                        <th>{{ $label }}</th>
                    @endif
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach ($assets as $asset)
                <tr>
                    @foreach($fields as $field => $label)
                        @if ($site->hasFeature(Feature::IS_WINTHROP) && strtoupper($asset->$field) === 'N/A')
                            <td class="pointer" onclick="window.document.location='{{ route('asset.details', ['id' => $asset->id ]) }}';">
                                {{ ' ' }}
                            </td>
                        @elseif (starts_with($field, 'hardcoded-'))
                            <td class="pointer" onclick="window.document.location='{{ route('asset.details', ['id' => $asset->id ]) }}';">
                                {{ str_replace('_', ' ', str_replace('hardcoded-', '', $field)) }}
                            </td>
                        @elseif(in_array($field, array_merge($fieldCategories['exact'], $fieldCategories['string_like'], $fieldCategories['string_multi'], $fieldCategories['custom'], $fieldCategories['int_less_greater'], $fieldCategories['float_less_greater']), true))
                            @if(($field === 'cert_of_data_wipe_num') || ($field === 'cert_of_destruction_num'))
                                <td title="{{ $asset->$field }}">
                            @else
                                <td class="pointer" title="{{ $asset->$field }}" onclick="window.document.location='{{ route('asset.details', ['id' => $asset->id ]) }}';">
                                    @endif
                                    @if ($asset->$field)
                                        @if (in_array($field, ['net_settlement', 'settlement_amount'], true))
                                            <span>{{ ' ' }}</span>
                                        @elseif($field === 'cert_of_data_wipe_num')
                                            @if ($site->hasFeature(Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER))
                                                @if (!$productFamilyArray = $site->getFeature(Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER)->pivot->data)
                                                    <?php $productFamilyArray = $site->getFeature(Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER)->data ?>
                                                @endif
                                                @if (in_array($asset->productFamily, $productFamilyArray))
                                                    @if (isset($asset->certOfDataWipeNum))
                                                        @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                            <?php $certOfDataWipePage = $site->pages->where('type', 'Certificates of Data Wipe')->first(); ?>
                                                            <?php $certOfDataWipe = ($certOfDataWipePage && isset($asset->shipment)) ? $asset->shipment->files->where('page_id', $certOfDataWipePage->id)->first() : null; ?>
                                                            @if ($certOfDataWipe)
                                                                @if ($site->hasFeature(Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE))
                                                                    <a href="{{ $certOfDataWipe->url }}" target="_blank">{{ str_limit($asset->certOfDataWipeNum, $limit) }} ({{ str_limit($certOfDataWipe->filename, $limit) }})</a>
                                                                @else
                                                                    <span>{{ str_limit($asset->certOfDataWipeNum, $limit) }} ({{ str_limit($certOfDataWipe->filename, $limit) }})</span>
                                                                @endif
                                                            @else
                                                                <span>{{ str_limit($asset->certOfDataWipeNum, $limit) }}</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        {{ ' ' }}
                                                    @endif
                                                @else
                                                    {{ ' ' }}
                                                @endif
                                            @else
                                                @if (isset($asset->certOfDataWipeNum))
                                                    @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                        <?php $certOfDataWipePage = $site->pages->where('type', 'Certificates of Data Wipe')->first(); ?>
                                                        <?php $certOfDataWipe = ($certOfDataWipePage && isset($asset->shipment)) ? $asset->shipment->files->where('page_id', $certOfDataWipePage->id)->first() : null; ?>
                                                        @if ($certOfDataWipe)
                                                            @if ($site->hasFeature(Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE))
                                                                <a href="{{ $certOfDataWipe->url }}" target="_blank">{{ str_limit($asset->certOfDataWipeNum, $limit) }} ({{ str_limit($certOfDataWipe->filename, $limit) }})</a>
                                                            @else
                                                                <span>{{ str_limit($asset->certOfDataWipeNum, $limit) }} ({{ str_limit($certOfDataWipe->filename, $limit) }})</span>
                                                            @endif
                                                        @else
                                                            <span>{{ str_limit($asset->certOfDataWipeNum, $limit) }}</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    {{ ' ' }}
                                                @endif
                                            @endif
                                        @elseif($field === 'cert_of_destruction_num')
                                            @if ($site->hasFeature(Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER))
                                                @if (!$statusArray = $site->getFeature(Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER)->pivot->data)
                                                    <?php $statusArray = $site->getFeature(Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER)->data ?>
                                                @endif
                                                @if (in_array($asset->status, $statusArray))
                                                    @if (isset($asset->certOfDestructionNum))
                                                        @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                            <?php $certOfDataDestructionPage = $site->pages->where('type', 'Certificates of Recycling')->first(); ?>
                                                            <?php $certOfDataDestruction = ($certOfDataDestructionPage && isset($asset->shipment)) ? $asset->shipment->files->where('page_id', $certOfDataDestructionPage->id)->first() : null; ?>
                                                            @if ($certOfDataDestruction)
                                                                @if ($site->hasFeature(Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE))
                                                                    <a href="{{ $certOfDataDestruction->url }}" target="_blank">{{ str_limit($asset->certOfDestructionNum, $limit) }} ({{ str_limit($certOfDataDestruction->filename, $limit) }})</a>
                                                                @else
                                                                    <span>{{ str_limit($asset->certOfDestructionNum, $limit) }} ({{ str_limit($certOfDataDestruction->filename, $limit) }})</span>
                                                                @endif
                                                            @else
                                                                <span>{{ str_limit($asset->certOfDestructionNum, $limit) }}</span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        {{ ' ' }}
                                                    @endif
                                                @else
                                                    {{ ' ' }}
                                                @endif
                                            @else
                                                @if (isset($asset->certOfDestructionNum))
                                                    @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                        <?php $certOfDataDestructionPage = $site->pages->where('type', 'Certificates of Recycling')->first(); ?>
                                                        <?php $certOfDataDestruction = ($certOfDataDestructionPage && isset($asset->shipment)) ? $asset->shipment->files->where('page_id', $certOfDataDestructionPage->id)->first() : null; ?>
                                                        @if ($certOfDataDestruction)
                                                            @if ($site->hasFeature(Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE))
                                                                <a href="{{ $certOfDataDestruction->url }}" target="_blank">{{ str_limit($asset->certOfDestructionNum, $limit) }} ({{ str_limit($certOfDataDestruction->filename, $limit) }})</a>
                                                            @else
                                                                <span>{{ str_limit($asset->certOfDestructionNum, $limit) }} ({{ str_limit($certOfDataDestruction->filename, $limit) }})</span>
                                                            @endif
                                                        @else
                                                            <span>{{ str_limit($asset->certOfDestructionNum, $limit) }}</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    {{ ' ' }}
                                                @endif
                                            @endif
                                        @else
                                            {{ str_limit($asset->$field, $limit) }}
                                        @endif
                                    @else
                                        @if($field === 'status')
                                            @if ($site->hasFeature(Feature::ASSET_CUSTOM_EMPTY_STATUS))
                                                @if (!$customStatus = $site->getFeature(Feature::ASSET_CUSTOM_EMPTY_STATUS)->pivot->data)
                                                    <?php $customStatus = $site->getFeature(Feature::ASSET_CUSTOM_EMPTY_STATUS)->data ?>
                                                @endif
                                                {{ str_limit($customStatus, $limit) }}
                                            @else
                                                {{ ' ' }}
                                            @endif
                                        @else
                                            {{ ' ' }}
                                        @endif
                                    @endif
                                </td>
                            @endif
                            @if(in_array($field, $fieldCategories['date_from_to'], true))
                                <td class="pointer" title="{{ $asset->$field }}" onclick="window.document.location='{{ route('asset.details', ['id' => $asset->id ]) }}';">{{ isset($asset->$field) ? $asset->$field->format(Constants::DATE_FORMAT) : ' ' }}</td>
                            @endif
                            @if(in_array($field, array_merge($fieldCategories['shipment']['exact'], $fieldCategories['shipment']['string_like'], $fieldCategories['shipment']['string_multi'], $fieldCategories['shipment']['custom'], $fieldCategories['shipment']['int_less_greater'], $fieldCategories['shipment']['float_less_greater']), true))
                                <td class="pointer" title="{{ isset($asset->shipment->$field) ? $asset->shipment->$field : '' }}" onclick="window.document.location='{{ route('asset.details', ['id' => $asset->id ]) }}';">
                                    @if(isset($asset->shipment))
                                        @if (in_array($field, ['freight_charge'], true))
                                            <span @if($asset->shipment->$field < 0)class="text-danger"@endif>{{ $asset->shipment->$field ? Constants::CURRENCY_SYMBOL . $asset->shipment->$field : ' ' }}</span>
                                        @else
                                            {{ $asset->shipment->$field ? str_limit($asset->shipment->$field, $limit) : ' ' }}
                                        @endif
                                    @else
                                        {{ ' ' }}
                                    @endif
                                </td>
                            @elseif(in_array($field, $fieldCategories['shipment']['date_from_to'], true))
                                <td class="pointer" title="{{ isset($asset->shipment->$field) ? $asset->shipment->$field : '' }}" onclick="window.document.location='{{ route('asset.details', ['id' => $asset->id ]) }}';">
                                    @if(isset($asset->shipment))
                                        {{ isset($asset->shipment->$field) ? $asset->shipment->$field->format(Constants::DATE_FORMAT) : ' ' }}
                                    @else
                                        {{ ' ' }}
                                    @endif
                                </td>
                            @endif
                            @if (starts_with($field, '!'))
                                <td class="pointer" onclick="window.document.location='{{ route('asset.details', ['id' => $asset->id ]) }}';">
                                    {{ ' ' }}
                                </td>
                            @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="container text-center">
        {{ $assets->appends(\Input::except('page'))->links() }}
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
            $('#assetSearchTable').stickyTableHeaders();

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
        });
    </script>
@endsection
