@extends('layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('shipment.details.shipment_details') {{ isset($shipment->lotNumber) ? ' - ' . ($shipment->lotNumber) : '' }}
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
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('lot_date', $fields) ? (Lang::has('shipment.'. $fields['lot_date']) ? Lang::trans('shipment.' .  $fields['lot_date']) :  $fields['lot_date']) : Lang::trans('shipment.lot_date') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->lotDate) ? $shipment->lotDate->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('lot_approved_date', $fields) ? (Lang::has('shipment.'. $fields['lot_approved_date']) ? Lang::trans('shipment.' .  $fields['lot_approved_date']) :  $fields['lot_approved_date']) : Lang::trans('shipment.lot_approved_date') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->lotApprovedDate) ? $shipment->lotApprovedDate->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('lot_number', $fields) ? (Lang::has('shipment.'. $fields['lot_number']) ? Lang::trans('shipment.' .  $fields['lot_number']) :  $fields['lot_number']) : Lang::trans('shipment.lot_number') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->lotNumber) ? $shipment->lotNumber : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('status', $fields) ? (Lang::has('shipment.'. $fields['status']) ? Lang::trans('shipment.' .  $fields['status']) :  $fields['status']) : Lang::trans('shipment.status') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->status) ? $shipment->status : '-' }}</p>
                                            </div>
                                        </div>

                                        <hr/>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('vendor', $fields) ? (Lang::has('shipment.'. $fields['vendor']) ? Lang::trans('shipment.' .  $fields['vendor']) :  $fields['vendor']) : Lang::trans('shipment.vendor') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->vendor) ? $shipment->vendor : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('vendor_client', $fields) ? (Lang::has('shipment.'. $fields['vendor_client']) ? Lang::trans('shipment.' .  $fields['vendor_client']) :  $fields['vendor_client']) : Lang::trans('shipment.vendor_client') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->vendorClient) ? $shipment->vendorClient : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('vendor_shipment_number', $fields) ? (Lang::has('shipment.'. $fields['vendor_shipment_number']) ? Lang::trans('shipment.' .  $fields['vendor_shipment_number']) :  $fields['vendor_shipment_number']) : Lang::trans('shipment.vendor_shipment_number') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->vendorShipmentNumber) ? $shipment->vendorShipmentNumber : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('po_number', $fields) ? (Lang::has('shipment.'. $fields['po_number']) ? Lang::trans('shipment.' .  $fields['po_number']) :  $fields['po_number']) : Lang::trans('shipment.po_number') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->poNumber) ? $shipment->poNumber : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('cost_center', $fields) ? (Lang::has('shipment.'. $fields['cost_center']) ? Lang::trans('shipment.' .  $fields['cost_center']) :  $fields['cost_center']) : Lang::trans('shipment.cost_center') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->costCenter) ? $shipment->costCenter : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('site_coordinator', $fields) ? (Lang::has('shipment.'. $fields['site_coordinator']) ? Lang::trans('shipment.' .  $fields['site_coordinator']) :  $fields['site_coordinator']) : Lang::trans('shipment.site_coordinator') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->siteCoordinator) ? $shipment->siteCoordinator : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('bill_of_lading', $fields) ? (Lang::has('shipment.'. $fields['bill_of_lading']) ? Lang::trans('shipment.' .  $fields['bill_of_lading']) :  $fields['bill_of_lading']) : Lang::trans('shipment.bill_of_lading') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->billOfLading) ? $shipment->billOfLading : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('city_of_origin', $fields) ? (Lang::has('shipment.'. $fields['city_of_origin']) ? Lang::trans('shipment.' .  $fields['city_of_origin']) :  $fields['city_of_origin']) : Lang::trans('shipment.city_of_origin') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->cityOfOrigin) ? $shipment->cityOfOrigin : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('freight_carrier', $fields) ? (Lang::has('shipment.'. $fields['freight_carrier']) ? Lang::trans('shipment.' .  $fields['freight_carrier']) :  $fields['freight_carrier']) : Lang::trans('shipment.freight_carrier') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->freightCarrier) ? $shipment->freightCarrier : '-' }}</p>
                                            </div>
                                        </div>

                                        @foreach(['inbound_tracking', 'outbound_tracking'] as $field)
                                            <div class="form-group margin-top-md margin-bottom-md">
                                                <label class="col-sm-6 control-label colon-after">{{ array_key_exists($field, $fields) ? (Lang::has('shipment.'. $fields[$field]) ? Lang::trans('shipment.' .  $fields[$field]) :  $fields[$field]) : Lang::trans('shipment.inbound_tracking') }}</label>
                                                <div class="col-sm-6">
                                                   @if (is_array($shipment->$field))
                                                        <select class="selectpicker form-control js-tracking-number-select">
                                                            <option value="">@lang('shipment.search_result.select_number_for_tracking')</option>
                                                            @foreach($shipment->$field as $key => $trackingNumber)
                                                                <option value="https://{{ $trackingNumber[1] }}">{{ $trackingNumber[0] }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        @if ($shipment->$field != '')
                                                            <?php $trackingNumber = explode('-COL-', $shipment->$field); ?>
                                                            <a href="https://{{ $trackingNumber[1] }}" target="_blank">{{ $trackingNumber[0] }}</a>
                                                        @else
                                                            <p class="form-control-static">{{ '-' }}</p>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach


                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('freight_invoice_number', $fields) ? (Lang::has('shipment.'. $fields['freight_invoice_number']) ? Lang::trans('shipment.' .  $fields['freight_invoice_number']) :  $fields['freight_invoice_number']) : Lang::trans('shipment.freight_invoice_number') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->freightInvoiceNumber) ? $shipment->freightInvoiceNumber : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('nf_received_date', $fields) ? (Lang::has('shipment.'. $fields['nf_received_date']) ? Lang::trans('shipment.' .  $fields['nf_received_date']) :  $fields['nf_received_date']) : Lang::trans('shipment.nf_received_date') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->nfReceivedDate) ? $shipment->nfReceivedDate->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('nota_fiscal_transfer', $fields) ? (Lang::has('shipment.'. $fields['nota_fiscal_transfer']) ? Lang::trans('shipment.' .  $fields['nota_fiscal_transfer']) :  $fields['nota_fiscal_transfer']) : Lang::trans('shipment.nota_fiscal_transfer') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->notaFiscalTransfer) ? $shipment->notaFiscalTransfer : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('nota_fiscal_transfer_2', $fields) ? (Lang::has('shipment.'. $fields['nota_fiscal_transfer_2']) ? Lang::trans('shipment.' .  $fields['nota_fiscal_transfer_2']) :  $fields['nota_fiscal_transfer_2']) : Lang::trans('shipment.nota_fiscal_transfer_2') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->notaFiscalTransfer2) ? $shipment->notaFiscalTransfer2 : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('nota_fiscal_transfer_3', $fields) ? (Lang::has('shipment.'. $fields['nota_fiscal_transfer_3']) ? Lang::trans('shipment.' .  $fields['nota_fiscal_transfer_3']) :  $fields['nota_fiscal_transfer_3']) : Lang::trans('shipment.nota_fiscal_transfer_3') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->notaFiscalTransfer3) ? $shipment->notaFiscalTransfer3 : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('nota_fiscal_transfer_4', $fields) ? (Lang::has('shipment.'. $fields['nota_fiscal_transfer_4']) ? Lang::trans('shipment.' .  $fields['nota_fiscal_transfer_4']) :  $fields['nota_fiscal_transfer_4']) : Lang::trans('shipment.nota_fiscal_transfer_4') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->notaFiscalTransfer4) ? $shipment->notaFiscalTransfer4 : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('nota_fiscal_transfer_5', $fields) ? (Lang::has('shipment.'. $fields['nota_fiscal_transfer_5']) ? Lang::trans('shipment.' .  $fields['nota_fiscal_transfer_5']) :  $fields['nota_fiscal_transfer_5']) : Lang::trans('shipment.nota_fiscal_transfer_5') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->notaFiscalTransfer5) ? $shipment->notaFiscalTransfer5 : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('schedule_pickup_date', $fields) ? (Lang::has('shipment.'. $fields['schedule_pickup_date']) ? Lang::trans('shipment.' .  $fields['schedule_pickup_date']) :  $fields['schedule_pickup_date']) : Lang::trans('shipment.schedule_pickup_date') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->schedulePickupDate) ? $shipment->schedulePickupDate->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('pickup_request_date', $fields) ? (Lang::has('shipment.'. $fields['pickup_request_date']) ? Lang::trans('shipment.' .  $fields['pickup_request_date']) :  $fields['pickup_request_date']) : Lang::trans('shipment.pickup_request_date') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->pickupRequestDate) ? $shipment->pickupRequestDate->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('actual_pickup_date', $fields) ? (Lang::has('shipment.'. $fields['actual_pickup_date']) ? Lang::trans('shipment.' .  $fields['actual_pickup_date']) :  $fields['actual_pickup_date']) : Lang::trans('shipment.actual_pickup_date') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->actualPickupDate) ? $shipment->actualPickupDate->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('pickup_address', $fields) ? (Lang::has('shipment.'. $fields['pickup_address']) ? Lang::trans('shipment.' .  $fields['pickup_address']) :  $fields['pickup_address']) : Lang::trans('shipment.pickup_address') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->pickupAddress) ? $shipment->pickupAddress : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('pickup_address_2', $fields) ? (Lang::has('shipment.'. $fields['pickup_address_2']) ? Lang::trans('shipment.' .  $fields['pickup_address_2']) :  $fields['pickup_address_2']) : Lang::trans('shipment.pickup_address_2') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->pickupAddress2) ? $shipment->pickupAddress2 : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('pickup_city', $fields) ? (Lang::has('shipment.'. $fields['pickup_city']) ? Lang::trans('shipment.' .  $fields['pickup_city']) :  $fields['pickup_city']) : Lang::trans('shipment.pickup_city') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->pickupCity) ? $shipment->pickupCity : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('pickup_state', $fields) ? (Lang::has('shipment.'. $fields['pickup_state']) ? Lang::trans('shipment.' .  $fields['pickup_state']) :  $fields['pickup_state']) : Lang::trans('shipment.pickup_state') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->pickupState) ? $shipment->pickupState : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('pickup_zip_code', $fields) ? (Lang::has('shipment.'. $fields['pickup_zip_code']) ? Lang::trans('shipment.' .  $fields['pickup_zip_code']) :  $fields['pickup_zip_code']) : Lang::trans('shipment.pickup_zip_code') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->pickupZipCode) ? $shipment->pickupZipCode : '-' }}</p>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('date_received', $fields) ? (Lang::has('shipment.'. $fields['date_received']) ? Lang::trans('shipment.' .  $fields['date_received']) :  $fields['date_received']) : Lang::trans('shipment.date_received') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->dateReceived) ? $shipment->dateReceived->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('equipment_summary', $fields) ? (Lang::has('shipment.'. $fields['equipment_summary']) ? Lang::trans('shipment.' .  $fields['equipment_summary']) :  $fields['equipment_summary']) : Lang::trans('shipment.equipment_summary') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->equipmentSummary) ? $shipment->equipmentSummary : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('total_weight_received', $fields) ? (Lang::has('shipment.'. $fields['total_weight_received']) ? Lang::trans('shipment.' .  $fields['total_weight_received']) :  $fields['total_weight_received']) : Lang::trans('shipment.total_weight_received') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->totalWeightReceived) ? $shipment->totalWeightReceived : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('number_of_skids', $fields) ? (Lang::has('shipment.'. $fields['number_of_skids']) ? Lang::trans('shipment.' .  $fields['number_of_skids']) :  $fields['number_of_skids']) : Lang::trans('shipment.number_of_skids') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->numberOfSkids) ? $shipment->numberOfSkids : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('number_of_pieces', $fields) ? (Lang::has('shipment.'. $fields['number_of_pieces']) ? Lang::trans('shipment.' .  $fields['number_of_pieces']) :  $fields['number_of_pieces']) : Lang::trans('shipment.number_of_pieces') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->numberOfPieces) ? $shipment->numberOfPieces : '-' }}</p>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('pre_audit_approved', $fields) ? (Lang::has('shipment.'. $fields['pre_audit_approved']) ? Lang::trans('shipment.' .  $fields['pre_audit_approved']) :  $fields['pre_audit_approved']) : Lang::trans('shipment.pre_audit_approved') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->preAuditApproved) ? $shipment->preAuditApproved->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('audit_completed', $fields) ? (Lang::has('shipment.'. $fields['audit_completed']) ? Lang::trans('shipment.' .  $fields['audit_completed']) :  $fields['audit_completed']) : Lang::trans('shipment.audit_completed') }}</label>
                                            <div class="col-sm-6">
                                                <p class="form-control-static">{{ isset($shipment->auditCompleted) ? $shipment->auditCompleted->format(Constants::DATE_FORMAT) : '-' }}</p>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('cert_of_data_wipe_num', $fields) ? (Lang::has('shipment.'. $fields['cert_of_data_wipe_num']) ? Lang::trans('shipment.' .  $fields['cert_of_data_wipe_num']) :  $fields['cert_of_data_wipe_num']) : Lang::trans('shipment.cert_of_data_wipe_num') }}</label>
                                            <div class="col-sm-6">
                                                @if (isset($shipment->certOfDataWipeNum))
                                                    @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                        <?php $certOfDataWipePage = $site->pages->where('type', 'Certificates of Data Wipe')->first(); ?>
                                                        <?php $certOfDataWipe = $certOfDataWipePage ? $shipment->files->where('page_id', $certOfDataWipePage->id)->first() : null; ?>
                                                        @if ($certOfDataWipe)
                                                            @if ($site->hasFeature(Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE))
                                                                <p class="form-control-static"><a href="{{ $certOfDataWipe->url }}" target="_blank">{{ $shipment->certOfDataWipeNum }} ({{ $certOfDataWipe->filename }})</a></p>
                                                            @else
                                                                <p class="form-control-static"><span>{{ $shipment->certOfDataWipeNum }} ({{ $certOfDataWipe->filename }})</span></p>
                                                            @endif
                                                        @else
                                                            <p class="form-control-static"><span>{{ $shipment->certOfDataWipeNum }}</span></p>
                                                        @endif
                                                    @endif
                                                @else
                                                    <p class="form-control-static">-</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label colon-after">{{ array_key_exists('cert_of_destruction_num', $fields) ? (Lang::has('shipment.'. $fields['cert_of_destruction_num']) ? Lang::trans('shipment.' .  $fields['cert_of_destruction_num']) :  $fields['cert_of_destruction_num']) : Lang::trans('shipment.cert_of_destruction_num') }}</label>
                                            <div class="col-sm-6">
                                                @if (isset($shipment->certOfDestructionNum))
                                                    @if ($site->hasFeature(Feature::HAS_CERTIFICATES))
                                                        <?php $certOfDataDestructionPage = $site->pages->where('type', 'Certificates of Recycling')->first(); ?>
                                                        <?php $certOfDataDestruction = $certOfDataDestructionPage ? $shipment->files->where('page_id', $certOfDataDestructionPage->id)->first() : null; ?>
                                                        @if ($certOfDataDestruction)
                                                            @if ($site->hasFeature(Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE))
                                                                <p class="form-control-static"><a href="{{ $certOfDataDestruction->url }}" target="_blank">{{ $shipment->certOfDestructionNum }} ({{ $certOfDataDestruction->filename }})</a></p>
                                                            @else
                                                                <p class="form-control-static"><span>{{ $shipment->certOfDestructionNum }} ({{ $certOfDataDestruction->filename }})</span></p>
                                                            @endif
                                                        @else
                                                            <p class="form-control-static"><span>{{ $shipment->certOfDestructionNum }}</span></p>
                                                        @endif
                                                    @endif
                                                @else
                                                    <p class="form-control-static">-</p>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($site->hasFeature(Feature::HAS_SETTLEMENTS))
                                            <?php $settlementPage = $site->pages->where('type', 'Settlements')->first(); ?>
                                            <?php $settlement = $settlementPage ? $shipment->files->where('page_id', $settlementPage->id)->first() : null; ?>
                                            @if ($settlement)
                                            <div class="form-group">
                                                <label class="col-sm-6 control-label colon-after">{{ array_key_exists('settlement', $fields) ? (Lang::has('shipment.'. $fields['settlement']) ? Lang::trans('shipment.' .  $fields['settlement']) :  $fields['settlement']) : Lang::trans('shipment.details.settlement') }}</label>
                                                <div class="col-sm-6">
                                                @if ($site->hasFeature(Feature::SETTLEMENT_AS_FILE))
                                                    <p class="form-control-static"><a href="{{ $settlement->url }}" target="_blank">{{ $settlement->filename }}</a></p>
                                                @else
                                                    <p class="form-control-static">{{ $settlement->filename }}</p>
                                                @endif
                                                </div>
                                            </div>
                                            @endif
                                        @endif

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

@section('js')
    <script>
        $(document).ready(function() {

            $('.js-tracking-number-select').on('change', function (event) {
                var $multiTrackNumSelect = $(this);
                if ($multiTrackNumSelect.val() !== '') {
                    window.open($multiTrackNumSelect.val());
                }
            });
        });
    </script>
@endsection
