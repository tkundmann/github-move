@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('main.layout.menu.remove_from_site')</div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-12">
                            @if (session()->has('assets_removed') && !empty(session('assets_removed')['successful']))
                                <div class="alert alert-success animate">
                                    @lang('admin.remove.remove_assets.successful_remove', ['quantity' => count(session('assets_removed')['successful']), 'asset' => trans_choice('admin.remove.remove_assets.asset', count(session('assets_removed')['successful']))])
                                </div>
                            @endif
                            @if (session()->has('assets_removed') && !empty(session('assets_removed')['unsuccessful']))
                                <div class="alert alert-danger animate">
                                    <span>@lang('admin.remove.remove_assets.unsuccessful_remove')</span>
                                    <span>@lang('admin.remove.remove_assets.barcodes_not_found') @foreach(session('assets_removed')['unsuccessful'] as $key => $barcode){{$barcode}}@if($key != count(session('assets_removed')['unsuccessful']) - 1), @endif{{----}}@endforeach.</span>
                                </div>
                            @endif
                            @if (session()->has('by_lot_number_removed') && !empty(session('by_lot_number_removed')) && (session('by_lot_number_removed')['asset'] > 0 || session('by_lot_number_removed')['shipment'] > 0))
                                <div class="alert alert-success animate">
                                    @lang('admin.remove.remove_by_lot_number.successful_remove', ['quantity_asset' => session('by_lot_number_removed')['asset'], 'asset' => trans_choice('admin.remove.remove_by_lot_number.asset', session('by_lot_number_removed')['asset']), 'quantity_shipment' => session('by_lot_number_removed')['shipment'], 'shipment' => trans_choice('admin.remove.remove_by_lot_number.shipment', session('by_lot_number_removed')['shipment'])])
                                </div>
                            @endif
                            @if ((session()->has('by_lot_number_error') && !empty(session('by_lot_number_error')) && session('by_lot_number_error') === true) || (session('by_lot_number_removed')['asset'] === 0 && session('by_lot_number_removed')['shipment'] === 0))
                                <div class="alert alert-danger animate">
                                    {{ trans('admin.remove.remove_by_lot_number.unsuccessful_remove', [ 'lot_number' => session('lot_number')]) }}
                                </div>
                            @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">@lang('admin.remove.remove_by_lot_number.remove_by_lot_number')</div>
                                    <div class="panel-body">
                                        <p>@lang('admin.remove.remove_by_lot_number.info')</p>
                                        <div class="alert alert-danger" role="alert">@lang('admin.remove.remove_by_lot_number.warning')</div>

                                        <form action="{{ route('admin.remove.byLotNumber') }}" method="POST" id="remove-by-lot-number-form">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <label for="lot_number" class="control-label colon-after">@lang('admin.remove.remove_by_lot_number.lot_number')</label>
                                                <div class="input-group">
                                                    <input id="lot_number" required="required"
                                                           placeholder="@lang('admin.remove.remove_by_lot_number.lot_number')"
                                                           name="lot_number" size="19"
                                                           class="form-control">
                                                    <span class="input-group-addon">@lang('common.in')</span>
                                                    <select id="table_type_select" name="table_type_select" class="selectpicker" title="@lang('admin.remove.remove_by_lot_number.select_table_type')" data-width="auto">
                                                        <option value="assets">@lang('admin.remove.remove_by_lot_number.assets_only')</option>
                                                        <option value="shipments">@lang('admin.remove.remove_by_lot_number.shipments_only')</option>
                                                        <option value="both">@lang('admin.remove.remove_by_lot_number.both')</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="text-center margin-top-lg margin-bottom-lg">
                                                <button type="button" id="remove-by-lot-number-button" class="btn btn-danger">
                                                    <i class="fa fa-btn fa-trash"></i>@lang('common.remove')
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">@lang('admin.remove.remove_assets.remove_assets')</div>
                                    <div class="panel-body">
                                        <p>@lang('admin.remove.remove_assets.info')</p>
                                        <div class="alert alert-danger" role="alert">@lang('admin.remove.remove_assets.warning')</div>

                                        <form id="remove-assets-form" action="{{ route('admin.remove.assets') }}" method="POST">
                                            {{ csrf_field() }}

                                            <div class="form-group">
                                                <label for="barcode_numbers" class="control-label colon-after">@lang('admin.remove.remove_assets.barcode_numbers')</label>
                                                <textarea id="barcode_numbers" name="barcode_numbers" required="required" class="form-control"></textarea>
                                            </div>

                                            <div class="text-center margin-top-lg">
                                                <button type="button" id="remove-assets-button" class="btn btn-danger">
                                                    <i class="fa fa-btn fa-trash"></i>@lang('admin.remove.remove_assets.remove_assets')
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    var removeByLotNumberButton = $('#remove-by-lot-number-button');
    var lotNumberInput = $('#lot_number');
    var tableTypeSelect = $('#table_type_select');

    updateRemoveByLotNumberButtonState();

    lotNumberInput.keyup(function() {
        updateRemoveByLotNumberButtonState();
    });

    tableTypeSelect.change(function() {
        updateRemoveByLotNumberButtonState();
    });

    function updateRemoveByLotNumberButtonState() {
        if ((lotNumberInput.val() == '') || (tableTypeSelect.val() == '')) {
            removeByLotNumberButton.prop('disabled', true);
        }
        else {
            removeByLotNumberButton.prop('disabled', false);
        }
    }

    removeByLotNumberButton.click(function () {
        var tableTypeTranslation = {
            'assets': '@lang('admin.remove.remove_by_lot_number.record_type_assets')',
            'shipments': '@lang('admin.remove.remove_by_lot_number.record_type_shipments')',
            'both': '@lang('admin.remove.remove_by_lot_number.record_type_both')'
        };
        var tableType = tableTypeTranslation[tableTypeSelect.val()];
        var headerText = "@lang('admin.remove.remove_by_lot_number.confirm_remove')".replace(':record_type', tableType);

        bootbox.dialog({
            message: lotNumberInput.val(),
            title: headerText,
            buttons: {
                ok: {
                    label: '@lang('common.remove')',
                    className: 'btn-danger',
                    callback: function() {
                        $('#remove-by-lot-number-form').submit();
                    }
                },
                cancel: {
                    label: '@lang('common.cancel')',
                    className: 'btn-default',
                    callback: function() {
                        // nothing
                    }
                }
            }
        });
    });

    var removeAssetsButton = $('#remove-assets-button');
    var barcodeNumbersInput = $('#barcode_numbers');

    updateRemoveAssetsButtonState();

    barcodeNumbersInput.keyup(function() {
        updateRemoveAssetsButtonState();
    });

    function updateRemoveAssetsButtonState() {
        if (barcodeNumbersInput.val() == '') {
            removeAssetsButton.prop('disabled', true);
        }
        else {
            removeAssetsButton.prop('disabled', false);
        }
    }

    removeAssetsButton.click(function () {
        var barcodeNumbers = barcodeNumbersInput.val();
        barcodeNumbers = barcodeNumbers.split(';').join(',');
        barcodeNumbers = barcodeNumbers.split(' ').join(',');
        barcodeNumbers = barcodeNumbers.split('\r\n').join(',');
        barcodeNumbers = barcodeNumbers.split('\r').join(',');
        barcodeNumbers = barcodeNumbers.split('\n').join(',');
        var barcodeNumbersArray = barcodeNumbers.split(',');
        var barcodeNumbersHtml = barcodeNumbersArray.map(function(element) {
            return element + '<br />';
        });

        bootbox.dialog({
            message: barcodeNumbersHtml,
            title: '@lang('admin.remove.remove_assets.confirm_remove')',
            buttons: {
                ok: {
                    label: '@lang('common.remove')',
                    className: 'btn-danger',
                    callback: function() {
                        $('#remove-assets-form').submit();
                    }
                },
                cancel: {
                    label: '@lang('common.cancel')',
                    className: 'btn-default',
                    callback: function() {
                        // nothing
                    }
                }
            }
        });
    });
});
</script>
@endsection