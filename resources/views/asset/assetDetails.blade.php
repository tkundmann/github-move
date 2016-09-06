@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('asset.details.asset_details') {{ isset($asset->barcodeNumber) ? ' - ' . $asset->barcodeNumber : '' }}
                        <div class="btn-group pull-right">
                            <button onclick="goBack()" class="btn btn-primary btn-xs"><i class="fa fa-btn fa-arrow-left"></i>@lang('common.back')</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="container-fluid formDetails">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('barcode_number', $fields) ? (Lang::has('asset.'. $fields['barcode_number']) ? Lang::trans('asset.' .  $fields['barcode_number']) :  $fields['barcode_number']) : Lang::trans('asset.barcode_number') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->barcodeNumber) ? $asset->barcodeNumber : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('manufacturer', $fields) ? (Lang::has('asset.'. $fields['manufacturer']) ? Lang::trans('asset.' .  $fields['manufacturer']) :  $fields['manufacturer']) : Lang::trans('asset.manufacturer') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->manufacturer) ? $asset->manufacturer : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('manufacturer_model_num', $fields) ? (Lang::has('asset.'. $fields['manufacturer_model_num']) ? Lang::trans('asset.' .  $fields['manufacturer_model_num']) :  $fields['manufacturer_model_num']) : Lang::trans('asset.manufacturer_model_num') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->manufacturerModelNum) ? $asset->manufacturerModelNum : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('manufacturer_part_num', $fields) ? (Lang::has('asset.'. $fields['manufacturer_part_num']) ? Lang::trans('asset.' .  $fields['manufacturer_part_num']) :  $fields['manufacturer_part_num']) : Lang::trans('asset.manufacturer_part_num') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->manufacturerPartNum) ? $asset->manufacturerPartNum : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('manufacturer_serial_num', $fields) ? (Lang::has('asset.'. $fields['manufacturer_serial_num']) ? Lang::trans('asset.' .  $fields['manufacturer_serial_num']) :  $fields['manufacturer_serial_num']) : Lang::trans('asset.manufacturer_serial_num') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->manufacturerSerialNum) ? $asset->manufacturerSerialNum : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('parent_serial_num', $fields) ? (Lang::has('asset.'. $fields['parent_serial_num']) ? Lang::trans('asset.' .  $fields['parent_serial_num']) :  $fields['parent_serial_num']) : Lang::trans('asset.parent_serial_num') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->parentSerialNum) ? $asset->parentSerialNum : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('item_number', $fields) ? (Lang::has('asset.'. $fields['item_number']) ? Lang::trans('asset.' .  $fields['item_number']) :  $fields['item_number']) : Lang::trans('asset.item_number') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->itemNumber) ? $asset->itemNumber : '-' }}</p>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('lot_date', $fields) ? (Lang::has('asset.'. $fields['lot_date']) ? Lang::trans('asset.' .  $fields['lot_date']) :  $fields['lot_date']) : Lang::trans('asset.lot_date') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->lotDate) ? $asset->lotDate->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('lot_number', $fields) ? (Lang::has('asset.'. $fields['lot_number']) ? Lang::trans('asset.' .  $fields['lot_number']) :  $fields['lot_number']) : Lang::trans('asset.lot_number') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->lotNumber) ? $asset->lotNumber : '-' }}</p>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('vendor', $fields) ? (Lang::has('asset.'. $fields['vendor']) ? Lang::trans('asset.' .  $fields['vendor']) :  $fields['vendor']) : Lang::trans('asset.vendor') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->vendor) ? $asset->vendor : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('vendor_client', $fields) ? (Lang::has('asset.'. $fields['vendor_client']) ? Lang::trans('asset.' .  $fields['vendor_client']) :  $fields['vendor_client']) : Lang::trans('asset.vendor_client') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->vendorClient) ? $asset->vendorClient : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('vendor_order_number', $fields) ? (Lang::has('asset.'. $fields['vendor_order_number']) ? Lang::trans('asset.' .  $fields['vendor_order_number']) :  $fields['vendor_order_number']) : Lang::trans('asset.vendor_order_number') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->vendorOrderNumber) ? $asset->vendorOrderNumber : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('carrier', $fields) ? (Lang::has('asset.'. $fields['carrier']) ? Lang::trans('asset.' .  $fields['carrier']) :  $fields['carrier']) : Lang::trans('asset.carrier') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->carrier) ? $asset->carrier : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('po_number', $fields) ? (Lang::has('asset.'. $fields['po_number']) ? Lang::trans('asset.' .  $fields['po_number']) :  $fields['po_number']) : Lang::trans('asset.po_number') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->poNumber) ? $asset->poNumber : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('bill_of_lading', $fields) ? (Lang::has('asset.'. $fields['bill_of_lading']) ? Lang::trans('asset.' .  $fields['bill_of_lading']) :  $fields['bill_of_lading']) : Lang::trans('asset.bill_of_lading') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->billOfLading) ? $asset->billOfLading : '-' }}</p>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('date_arrived', $fields) ? (Lang::has('asset.'. $fields['date_arrived']) ? Lang::trans('asset.' .  $fields['date_arrived']) :  $fields['date_arrived']) : Lang::trans('asset.date_arrived') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->dateArrived) ? $asset->dateArrived->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('shipment_date', $fields) ? (Lang::has('asset.'. $fields['shipment_date']) ? Lang::trans('asset.' .  $fields['shipment_date']) :  $fields['shipment_date']) : Lang::trans('asset.shipment_date') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->shipmentDate) ? $asset->shipmentDate->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('comments', $fields) ? (Lang::has('asset.'. $fields['comments']) ? Lang::trans('asset.' .  $fields['comments']) :  $fields['comments']) : Lang::trans('asset.comments') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->comments) ? $asset->comments : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('additional_comments', $fields) ? (Lang::has('asset.'. $fields['additional_comments']) ? Lang::trans('asset.' .  $fields['additional_comments']) :  $fields['additional_comments']) : Lang::trans('asset.additional_comments') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->additionalComments) ? $asset->additionalComments : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('status', $fields) ? (Lang::has('asset.'. $fields['status']) ? Lang::trans('asset.' .  $fields['status']) :  $fields['status']) : Lang::trans('asset.status') }}</label>
                                            <div class="col-sm-6">
                                                @if (isset($asset->status))
                                                    <p class="form-control-static">{{ $asset->status }}</p>
                                                @else
                                                    @if ($site->hasFeature(Feature::ASSET_CUSTOM_EMPTY_STATUS))
                                                        @if (!$customStatus = $site->getFeature(Feature::ASSET_CUSTOM_EMPTY_STATUS)->pivot->data)
                                                            <?php $customStatus = $site->getFeature(Feature::ASSET_CUSTOM_EMPTY_STATUS)->data ?>
                                                        @endif
                                                        <p class="form-control-static">{{ $customStatus }}</p>
                                                    @else
                                                        <p class="form-control-static">-</p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('settlement_amount', $fields) ? (Lang::has('asset.'. $fields['settlement_amount']) ? Lang::trans('asset.' .  $fields['settlement_amount']) :  $fields['settlement_amount']) : Lang::trans('asset.settlement_amount') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static @if($asset->settlementAmount && $asset->settlementAmount < 0) text-danger @endif">{{ isset($asset->settlementAmount) ? Constants::CURRENCY_SYMBOL . $asset->settlementAmount : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('net_settlement', $fields) ? (Lang::has('asset.'. $fields['net_settlement']) ? Lang::trans('asset.' .  $fields['net_settlement']) :  $fields['net_settlement']) : Lang::trans('asset.net_settlement') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static @if($asset->netSettlement && $asset->netSettlement < 0) text-danger @endif">{{ isset($asset->netSettlement) ? Constants::CURRENCY_SYMBOL . $asset->netSettlement : '-' }}</p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('product_family', $fields) ? (Lang::has('asset.'. $fields['product_family']) ? Lang::trans('asset.' .  $fields['product_family']) :  $fields['product_family']) : Lang::trans('asset.product_family') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->productFamily) ? $asset->productFamily : '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('form_factor', $fields) ? (Lang::has('asset.'. $fields['form_factor']) ? Lang::trans('asset.' .  $fields['form_factor']) :  $fields['form_factor']) : Lang::trans('asset.form_factor') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->formFactor) ? $asset->formFactor : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('speed', $fields) ? (Lang::has('asset.'. $fields['speed']) ? Lang::trans('asset.' .  $fields['speed']) :  $fields['speed']) : Lang::trans('asset.speed') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->speed) ? $asset->speed : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('memory', $fields) ? (Lang::has('asset.'. $fields['memory']) ? Lang::trans('asset.' .  $fields['memory']) :  $fields['memory']) : Lang::trans('asset.memory') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->memory) ? $asset->memory : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('storage_capacity', $fields) ? (Lang::has('asset.'. $fields['storage_capacity']) ? Lang::trans('asset.' .  $fields['storage_capacity']) :  $fields['storage_capacity']) : Lang::trans('asset.storage_capacity') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->storageCapacity) ? $asset->storageCapacity : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('dual', $fields) ? (Lang::has('asset.'. $fields['dual']) ? Lang::trans('asset.' .  $fields['dual']) :  $fields['dual']) : Lang::trans('asset.dual') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->dual) ? $asset->dual : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('quad', $fields) ? (Lang::has('asset.'. $fields['quad']) ? Lang::trans('asset.' .  $fields['quad']) :  $fields['quad']) : Lang::trans('asset.quad') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->quad) ? $asset->quad : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('optical_1', $fields) ? (Lang::has('asset.'. $fields['optical_1']) ? Lang::trans('asset.' .  $fields['optical_1']) :  $fields['optical_1']) : Lang::trans('asset.optical_1') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->optical1) ? $asset->optical1 : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('optical_2', $fields) ? (Lang::has('asset.'. $fields['optical_2']) ? Lang::trans('asset.' .  $fields['optical_2']) :  $fields['optical_2']) : Lang::trans('asset.optical_2') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->optical2) ? $asset->optical2 : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('nic', $fields) ? (Lang::has('asset.'. $fields['nic']) ? Lang::trans('asset.' .  $fields['nic']) :  $fields['nic']) : Lang::trans('asset.nic') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->nic) ? $asset->nic : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('video', $fields) ? (Lang::has('asset.'. $fields['video']) ? Lang::trans('asset.' .  $fields['video']) :  $fields['video']) : Lang::trans('asset.video') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->video) ? $asset->video : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('color', $fields) ? (Lang::has('asset.'. $fields['color']) ? Lang::trans('asset.' .  $fields['color']) :  $fields['color']) : Lang::trans('asset.color') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->color) ? $asset->color : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('adapter', $fields) ? (Lang::has('asset.'. $fields['adapter']) ? Lang::trans('asset.' .  $fields['adapter']) :  $fields['adapter']) : Lang::trans('asset.adapter') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->adapter) ? $asset->adapter : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('screen_size', $fields) ? (Lang::has('asset.'. $fields['screen_size']) ? Lang::trans('asset.' .  $fields['screen_size']) :  $fields['screen_size']) : Lang::trans('asset.screen_size') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->screenSize) ? $asset->screenSize : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('battery', $fields) ? (Lang::has('asset.'. $fields['battery']) ? Lang::trans('asset.' .  $fields['battery']) :  $fields['battery']) : Lang::trans('asset.battery') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->battery) ? $asset->battery : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('wifi', $fields) ? (Lang::has('asset.'. $fields['wifi']) ? Lang::trans('asset.' .  $fields['wifi']) :  $fields['wifi']) : Lang::trans('asset.wifi') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->wifi) ? $asset->wifi : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('docking_station', $fields) ? (Lang::has('asset.'. $fields['docking_station']) ? Lang::trans('asset.' .  $fields['docking_station']) :  $fields['docking_station']) : Lang::trans('asset.docking_station') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->dockingStation) ? $asset->dockingStation : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('stylus', $fields) ? (Lang::has('asset.'. $fields['stylus']) ? Lang::trans('asset.' .  $fields['stylus']) :  $fields['stylus']) : Lang::trans('asset.stylus') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->stylus) ? $asset->stylus : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('firewire', $fields) ? (Lang::has('asset.'. $fields['firewire']) ? Lang::trans('asset.' .  $fields['firewire']) :  $fields['firewire']) : Lang::trans('asset.firewire') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->firewire) ? $asset->firewire : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('keyboard', $fields) ? (Lang::has('asset.'. $fields['keyboard']) ? Lang::trans('asset.' .  $fields['keyboard']) :  $fields['keyboard']) : Lang::trans('asset.keyboard') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->keyboard) ? $asset->keyboard : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('mouse', $fields) ? (Lang::has('asset.'. $fields['mouse']) ? Lang::trans('asset.' .  $fields['mouse']) :  $fields['mouse']) : Lang::trans('asset.mouse') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->mouse) ? $asset->mouse : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('cartridge', $fields) ? (Lang::has('asset.'. $fields['cartridge']) ? Lang::trans('asset.' .  $fields['cartridge']) :  $fields['cartridge']) : Lang::trans('asset.cartridge') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->cartridge) ? $asset->cartridge : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('coa', $fields) ? (Lang::has('asset.'. $fields['coa']) ? Lang::trans('asset.' .  $fields['coa']) :  $fields['coa']) : Lang::trans('asset.coa') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->coa) ? $asset->coa : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('osx_description', $fields) ? (Lang::has('asset.'. $fields['osx_description']) ? Lang::trans('asset.' .  $fields['osx_description']) :  $fields['osx_description']) : Lang::trans('asset.osx_description') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->osxDescription) ? $asset->osxDescription : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('condition', $fields) ? (Lang::has('asset.'. $fields['condition']) ? Lang::trans('asset.' .  $fields['condition']) :  $fields['condition']) : Lang::trans('asset.condition') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->condition) ? $asset->condition : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('date_code', $fields) ? (Lang::has('asset.'. $fields['date_code']) ? Lang::trans('asset.' .  $fields['date_code']) :  $fields['date_code']) : Lang::trans('asset.date_code') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->dateCode) ? $asset->dateCode : '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('hard_drive_serial_num', $fields) ? (Lang::has('asset.'. $fields['hard_drive_serial_num']) ? Lang::trans('asset.' .  $fields['hard_drive_serial_num']) :  $fields['hard_drive_serial_num']) : Lang::trans('asset.hard_drive_serial_num') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->hardDriveSerialNum) ? $asset->hardDriveSerialNum : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('asset_tag', $fields) ? (Lang::has('asset.'. $fields['asset_tag']) ? Lang::trans('asset.' .  $fields['asset_tag']) :  $fields['asset_tag']) : Lang::trans('asset.asset_tag') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($asset->assetTag) ? $asset->assetTag : '-' }}</p>
                                            </div>
                                        </div>
                                        <hr />

                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('cert_of_data_wipe_num', $fields) ? (Lang::has('asset.'. $fields['cert_of_data_wipe_num']) ? Lang::trans('asset.' .  $fields['cert_of_data_wipe_num']) :  $fields['cert_of_data_wipe_num']) : Lang::trans('asset.cert_of_data_wipe_num') }}</label>
                                            <div class="col-sm-6">
                                                @if ($site->hasFeature(Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER))
                                                    @if (!$productFamilyArray = $site->getFeature(Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER)->pivot->data)
                                                    <?php $productFamilyArray = $site->getFeature(Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER)->data ?>
                                                    @endif
                                                    @if (in_array($asset->productFamily, $productFamilyArray))
                                                        @if (isset($asset->certOfDataWipeNum))
                                                            @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                                <?php $certOfDataWipePage = $site->pages->where('type', 'Certificates of Data Wipe')->first(); ?>
                                                                <?php $certOfDataWipe = $certOfDataWipePage ? $asset->shipment->files->where('page_id', $certOfDataWipePage->id)->first() : null; ?>
                                                                @if ($certOfDataWipe)
                                                                    @if ($site->hasFeature(Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE))
                                                                        <p class="form-control-static"><a href="{{ $certOfDataWipe->url }}" target="_blank">{{ $asset->certOfDataWipeNum }} ({{ $certOfDataWipe->filename }})</a></p>
                                                                    @else
                                                                        <p class="form-control-static"><span>{{ $asset->certOfDataWipeNum }} ({{ $certOfDataWipe->filename }})</span></p>
                                                                    @endif
                                                                @else
                                                                        <p class="form-control-static"><span>{{ $asset->certOfDataWipeNum }}</span></p>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <p class="form-control-static">-</p>
                                                        @endif
                                                    @else
                                                        <p class="form-control-static">-</p>
                                                    @endif
                                                @else
                                                    @if (isset($asset->certOfDataWipeNum))
                                                        @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                            <?php $certOfDataWipePage = $site->pages->where('type', 'Certificates of Data Wipe')->first(); ?>
                                                            <?php $certOfDataWipe = $certOfDataWipePage ? $asset->shipment->files->where('page_id', $certOfDataWipePage->id)->first() : null; ?>
                                                            @if ($certOfDataWipe)
                                                                @if ($site->hasFeature(Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE))
                                                                    <p class="form-control-static"><a href="{{ $certOfDataWipe->url }}" target="_blank">{{ $asset->certOfDataWipeNum }} ({{ $certOfDataWipe->filename }})</a></p>
                                                                @else
                                                                    <p class="form-control-static"><span>{{ $asset->certOfDataWipeNum }} ({{ $certOfDataWipe->filename }})</span></p>
                                                                @endif
                                                            @else
                                                                <p class="form-control-static"><span>{{ $asset->certOfDataWipeNum }}</span></p>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <p class="form-control-static">-</p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('cert_of_destruction_num', $fields) ? (Lang::has('asset.'. $fields['cert_of_destruction_num']) ? Lang::trans('asset.' .  $fields['cert_of_destruction_num']) :  $fields['cert_of_destruction_num']) : Lang::trans('asset.cert_of_destruction_num') }}</label>
                                            <div class="col-sm-6">
                                                @if ($site->hasFeature(Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER))
                                                    @if (!$statusArray = $site->getFeature(Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER)->pivot->data)
                                                    <?php $statusArray = $site->getFeature(Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER)->data ?>
                                                    @endif
                                                    @if (in_array($asset->status, $statusArray))
                                                        @if (isset($asset->certOfDestructionNum))
                                                            @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                                <?php $certOfDataDestructionPage = $site->pages->where('type', 'Certificates of Recycling')->first(); ?>
                                                                <?php $certOfDataDestruction = $certOfDataDestructionPage ? $asset->shipment->files->where('page_id', $certOfDataDestructionPage->id)->first() : null; ?>
                                                                @if ($certOfDataDestruction)
                                                                    @if ($site->hasFeature(Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE))
                                                                        <p class="form-control-static"><a href="{{ $certOfDataDestruction->url }}" target="_blank">{{ $asset->certOfDestructionNum }} ({{ $certOfDataDestruction->filename }})</a></p>
                                                                    @else
                                                                        <p class="form-control-static"><span>{{ $asset->certOfDestructionNum }} ({{ $certOfDataDestruction->filename }})</span></p>
                                                                    @endif
                                                                @else
                                                                    <p class="form-control-static"><span>{{ $asset->certOfDestructionNum }}</span></p>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <p class="form-control-static">-</p>
                                                        @endif
                                                    @else
                                                            <p class="form-control-static">-</p>
                                                    @endif
                                                @else
                                                    @if (isset($asset->certOfDestructionNum))
                                                        @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                            <?php $certOfDataDestructionPage = $site->pages->where('type', 'Certificates of Recycling')->first(); ?>
                                                            <?php $certOfDataDestruction = $certOfDataDestructionPage ? $asset->shipment->files->where('page_id', $certOfDataDestructionPage->id)->first() : null; ?>
                                                            @if ($certOfDataDestruction)
                                                                @if ($site->hasFeature(Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE))
                                                                    <p class="form-control-static"><a href="{{ $certOfDataDestruction->url }}" target="_blank">{{ $asset->certOfDestructionNum }} ({{ $certOfDataDestruction->filename }})</a></p>
                                                                @else
                                                                    <p class="form-control-static"><span>{{ $asset->certOfDestructionNum }} ({{ $certOfDataDestruction->filename }})</span></p>
                                                                @endif
                                                            @else
                                                                <p class="form-control-static"><span>{{ $asset->certOfDestructionNum }}</span></p>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <p class="form-control-static">-</p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
